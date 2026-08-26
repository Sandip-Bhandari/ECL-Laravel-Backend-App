<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $q = Task::with(['department', 'assignee', 'creator', 'comments.user', 'attachments'])->latest();
        
        // 🟢 Hierarchy Rule: Agar user regular employee hai
        if ($user->role === 'employee') {
            $q->where('assigned_to', $user->id);
        } 
        // 🟢 Agar Manager/Admin hai toh khud ke + subordinates ke tasks dikhenge
        elseif ($user->role === 'manager' || $user->role === 'admin' || $user->role === 'super_admin') {
            $subordinateIds = $user->children()->pluck('id')->toArray();
            $allowedUserIds = array_merge([$user->id], $subordinateIds);
            
            $q->where(function($query) use ($user, $allowedUserIds) {
                $query->whereIn('assigned_to', $allowedUserIds)
                      ->orWhere('created_by', $user->id);
            });
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $q->where('department_id', $request->integer('department_id'));
        }
        if ($request->filled('assigned_to')) {
            $q->where('assigned_to', $request->integer('assigned_to'));
        }
        if ($request->filled('search')) {
            $q->where(fn($x) => $x->where('title', 'like', '%' . $request->search . '%')
                                    ->orWhere('description', 'like', '%' . $request->search . '%'));
        }

        return response()->json($q->get()->map(fn($t) => $this->payload($t)));
    }

    public function getEmployeeTasks(Request $request)
    {
        $user = $request->user();
        
        $tasks = Task::with(['department', 'assignee', 'creator', 'comments.user', 'attachments'])
                    ->where('assigned_to', $user->id)
                    ->latest()
                    ->get();

        return response()->json($tasks->map(fn($t) => $this->payload($t)));
    }

    public function show(Request $request, Task $task) 
    { 
        $this->authorizeView($request->user(), $task); 
        return response()->json($this->payload($task->load(['department', 'assignee', 'creator', 'comments.user', 'attachments']))); 
    }

    public function store(Request $request)
    {
        $currentUser = $request->user();

        if ($request->has('department') && !$request->has('department_id')) {
            $deptName = $request->input('department');
            $dept = \App\Models\Department::where('name', $deptName)->first();
            if ($dept) {
                $request->merge(['department_id' => $dept->id]);
            }
        }

        // 🟢 Updated validation rules (Added 'priority' and removed 'employee_email')
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => ['required', Rule::in(['High', 'Medium', 'Low'])], // Added priority
            'status' => ['nullable', Rule::in(['Pending', 'In Progress', 'Completed'])],
            'due_date' => 'nullable|date_format:Y-m-d',
            'attachment' => 'nullable|file|max:10240'
        ]);

        // 🟢 Hierarchy Validation for Task Assignment
        if (isset($data['assigned_to']) && !in_array($currentUser->role, ['super_admin', 'admin'])) {
            $isSubordinate = $currentUser->children()->where('id', $data['assigned_to'])->exists();
            if (!$isSubordinate) {
                return response()->json([
                    'message' => 'Unauthorized: You can only assign tasks to your direct reporting subordinates.'
                ], 403);
            }
        }

        $attachment = $request->file('attachment'); 
        unset($data['attachment']);

        $data['created_by'] = $currentUser->id; 
        $data['status'] = $data['status'] ?? 'Pending';

        $tasksCreated = DB::transaction(function() use ($data, $attachment, $request, $currentUser) {
            $createdTasks = [];

            $task = Task::create($data); 
            
            if ($attachment) {
                $this->saveAttachment($task, $attachment, $currentUser->id);
            } 
            
            if (!empty($data['assigned_to'])) {
                AppNotification::create([
                    'user_id' => $data['assigned_to'],
                    'task_id' => $task->id,
                    'title' => 'New Task Assigned',
                    'message' => 'You have been assigned: ' . $task->title,
                    'type' => 'task_assigned'
                ]);
            }

            $createdTasks[] = $task;
            
            return $createdTasks;
        });

        $primaryTask = $tasksCreated[0] ?? null;

        return response()->json([
            'message' => 'Task created and assigned successfully within hierarchy.',
            'task' => $primaryTask ? $this->payload($primaryTask->load(['department', 'assignee', 'creator', 'attachments'])) : null
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        $currentUser = $request->user();

        // 🟢 Security: Ensure non-admins can only modify tasks they created or are assigned to their chain
        if (!in_array($currentUser->role, ['super_admin', 'admin'])) {
            $isSubordinateTask = $currentUser->children()->where('id', $task->assigned_to)->exists();
            if ($task->created_by !== $currentUser->id && !$isSubordinateTask) {
                return response()->json(['message' => 'Unauthorized to update this task.'], 403);
            }
        }

        // 🟢 Updated validation rules (Added 'priority' support for updates)
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => ['sometimes', Rule::in(['High', 'Medium', 'Low'])], // Added priority
            'status' => [Rule::in(['Pending', 'In Progress', 'Completed'])],
            'due_date' => 'nullable|date_format:Y-m-d',
            'attachment' => 'nullable|file|max:10240'
        ]);

        $attachment = $request->file('attachment'); 
        unset($data['attachment']);

        if (array_key_exists('status', $data)) {
            $data['completed_at'] = $data['status'] === 'Completed' ? ($task->completed_at ?? now()) : null;
        }

        $oldAssignee = $task->assigned_to;

        DB::transaction(function() use ($task, $data, $attachment, $request, $oldAssignee) {
            $task->update($data); 
            
            if ($attachment) {
                $this->saveAttachment($task, $attachment, $request->user()->id);
            } 
            
            if (!empty($data['assigned_to']) && $data['assigned_to'] != $oldAssignee) {
                AppNotification::create([
                    'user_id' => $data['assigned_to'],
                    'task_id' => $task->id,
                    'title' => 'Task Assigned',
                    'message' => 'You have been assigned: ' . $task->title,
                    'type' => 'task_assigned'
                ]);
            } 
        });

        return response()->json([
            'message' => 'Task updated.',
            'task' => $this->payload($task->fresh(['department', 'assignee', 'creator', 'comments.user', 'attachments']))
        ]);
    }

    public function updateMyTask(Request $request, Task $task)
    {
        abort_unless($task->assigned_to === $request->user()->id, 403, 'This task is not assigned to you.');
        
        $data = $request->validate([
            'status' => [Rule::in(['Pending', 'In Progress', 'Completed'])]
        ]);
        
        $data['completed_at'] = ($data['status'] ?? $task->status) === 'Completed' ? now() : null;
        $task->update($data);
        
        if ($task->creator) {
            AppNotification::create([
                'user_id' => $task->creator->id,
                'task_id' => $task->id,
                'title' => 'Task Status Updated',
                'message' => $request->user()->name . ' changed "' . $task->title . '" to ' . $task->status . '.',
                'type' => 'task_status'
            ]);
        }

        return response()->json([
            'message' => 'Task status updated by employee.',
            'task' => $this->payload($task->fresh(['department', 'assignee']))
        ]);
    }

    public function destroy(Request $request, Task $task) 
    { 
        $currentUser = $request->user();
        if (!in_array($currentUser->role, ['super_admin', 'admin']) && $task->created_by !== $currentUser->id) {
            return response()->json(['message' => 'Unauthorized to delete this task.'], 403);
        }

        $task->delete(); 
        return response()->json(['message' => 'Task deleted.']); 
    }

    private function saveAttachment(Task $task, $file, $userId): void 
    { 
        $path = $file->store('tasks/' . $task->id, 'public'); 
        TaskAttachment::create([
            'task_id' => $task->id,
            'uploaded_by' => $userId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize()
        ]); 
    }

    private function authorizeView(User $user, Task $task): void 
    { 
        if ($user->role === 'employee' && $task->assigned_to !== $user->id) {
            abort(403, 'This task is not assigned to you.');
        } 
    }

    private function payload(Task $t): array 
    { 
        return [
            'id' => $t->id,
            'title' => $t->title,
            'description' => $t->description,
            'status' => $t->status,
            'priority' => $t->priority ?? 'Medium', // Added priority in payload response
            'due_date' => $t->due_date?->format('Y-m-d'),
            'is_overdue' => $t->is_overdue,
            'department_id' => $t->department_id,
            'department' => $t->department?->name,
            'assigned_to' => $t->assigned_to,
            'assigned_employee' => $t->assignee?->name,
            'created_by' => $t->created_by,
            'created_by_name' => $t->creator?->name,
            'completed_at' => $t->completed_at?->toISOString(),
            'comments' => $t->relationLoaded('comments') ? $t->comments->map(fn($c) => [
                'id' => $c->id,
                'task_id' => $c->task_id,
                'user_id' => $c->user_id,
                'user_name' => $c->user?->name,
                'comment' => $c->comment,
                'created_at' => $c->created_at?->toISOString()
            ]) : [],
            'attachments' => $t->relationLoaded('attachments') ? $t->attachments->map(fn($a) => [
                'id' => $a->id,
                'file_name' => $a->file_name,
                'mime_type' => $a->mime_type,
                'file_size' => $a->file_size,
                'url' => $a->url
            ]) : []
        ]; 
    }
}