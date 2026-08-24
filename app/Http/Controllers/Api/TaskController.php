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
        
        // Agar logged-in user employee hai, toh use sirf uske received/assigned tasks dikhenge
        if ($user->role === 'employee') {
            $q->where('assigned_to', $user->id);
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

    // --- EMPLOYEE KE EXPLICIT URL KE LIYE NAYA METHOD ---
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
        // Agar app se department_id ki jagah department name ya string aa raha ho toh usko handle karne ke liye
        if ($request->has('department') && !$request->has('department_id')) {
            $deptName = $request->input('department');
            $dept = \App\Models\Department::where('name', $deptName)->first();
            if ($dept) {
                $request->merge(['department_id' => $dept->id]);
            }
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'employee_email' => 'nullable|string', // Multi-emails comma-separated string ke roop me accept hogi
            'assigned_to' => 'nullable|exists:users,id',
            'status' => ['nullable', Rule::in(['Pending', 'In Progress', 'Completed'])],
            'due_date' => 'nullable|date_format:Y-m-d',
            'attachment' => 'nullable|file|max:10240'
        ]);

        $attachment = $request->file('attachment'); 
        unset($data['attachment']);

        $data['created_by'] = $request->user()->id; 
        $data['status'] = $data['status'] ?? 'Pending';

        $employeeEmailsInput = $data['employee_email'] ?? null;
        unset($data['employee_email']);

        $tasksCreated = DB::transaction(function() use ($data, $attachment, $request, $employeeEmailsInput) {
            $createdTasks = [];

            // Agar multiple emails comma-separated aayi hain
            if (!empty($employeeEmailsInput)) {
                $emails = explode(',', $employeeEmailsInput);
                
                foreach ($emails as $email) {
                    $email = trim($email);
                    $employee = User::where('email', $email)->where('role', 'employee')->first();
                    
                    if ($employee) {
                        $taskData = $data;
                        $taskData['assigned_to'] = $employee->id;
                        
                        $task = Task::create($taskData); 
                        
                        if ($attachment) {
                            $this->saveAttachment($task, $attachment, $request->user()->id);
                        } 
                        
                        AppNotification::create([
                            'user_id' => $employee->id,
                            'task_id' => $task->id,
                            'title' => 'New Task Assigned',
                            'message' => 'You have been assigned: ' . $task->title,
                            'type' => 'task_assigned'
                        ]);

                        $createdTasks[] = $task;
                    }
                }
            } else {
                // Agar group broadcast ya bina specific employee ke task hai
                $task = Task::create($data); 
                if ($attachment) {
                    $this->saveAttachment($task, $attachment, $request->user()->id);
                } 
                $createdTasks[] = $task;
            }
            
            return $createdTasks;
        });

        $primaryTask = $tasksCreated[0] ?? null;

        return response()->json([
            'message' => 'Task(s) created and assigned successfully.',
            'task' => $primaryTask ? $this->payload($primaryTask->load(['department', 'assignee', 'creator', 'attachments'])) : null
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => [Rule::in(['Pending', 'In Progress', 'Completed'])],
            'due_date' => 'nullable|date_format:Y-m-d',
            'attachment' => 'nullable|file|max:10240'
        ]);

        $attachment = $request->file('attachment'); 
        unset($data['attachment']);

        if (($data['status'] ?? $task->status) === 'Completed') {
            $data['completed_at'] = $task->completed_at ?? now(); 
        } else {
            $data['completed_at'] = null;
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

    public function destroy(Task $task) 
    { 
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