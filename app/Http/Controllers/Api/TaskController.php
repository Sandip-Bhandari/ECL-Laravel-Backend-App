<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use App\Services\FcmPushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
   public function index(Request $request)
    {
        $user = $request->user();
        
        $q = Task::with(['department', 'assignee', 'creator', 'comments.user', 'attachments'])
            ->latest();

        // Agar user Admin ya Super Admin nahi hai, tabhi restriction lagayein
        if (!$this->hasAdminRole($user)) {
            $q->where(function($query) use ($user) {
                $query->where('created_by', $user->id)
                      ->orWhere('assigned_to', $user->id);
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
        
        // Filter for created_by (Includes both created and assigned tasks for that user)
        if ($request->filled('created_by')) {
            $userId = $request->integer('created_by');
            $q->where(function($query) use ($userId) {
                $query->where('created_by', $userId)
                      ->orWhere('assigned_to', $userId);
            });
        }

        return response()->json($q->get()->map(fn($t) => $this->payload($t)));
    }

    public function getEmployeeTasks(Request $request)
    {
        $user = $request->user();
        
        $tasks = Task::with(['department', 'assignee', 'creator', 'comments.user', 'attachments'])
            ->where(function($query) use ($user) {
                $query->where('created_by', $user->id)
                      ->orWhere('assigned_to', $user->id);
            })
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
        try {
            $currentUser = $request->user(); 

            // 1. Department Name to ID conversion with safety
            if ($request->has('department') && !$request->has('department_id')) {
                $deptName = $request->input('department');
                $dept = \App\Models\Department::where('name', 'like', '%' . $deptName . '%')->first();
                if ($dept) {
                    $request->merge(['department_id' => $dept->id]);
                } else {
                    return response()->json([
                        'message' => "Department '{$deptName}' database mein nahi mila."
                    ], 422);
                }
            }

            // 2. Employee Name to ID conversion with safety
            if ($request->has('assigned_to') && !is_numeric($request->input('assigned_to'))) {
                $empName = $request->input('assigned_to');
                $employee = \App\Models\User::where('name', 'like', '%' . $empName . '%')->first();
                if ($employee) {
                    $request->merge(['assigned_to' => $employee->id]);
                } else {
                    return response()->json([
                        'message' => "Employee '{$empName}' database mein nahi mila. Kripya sahi naam chunein."
                    ], 422);
                }
            }

            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'department_id' => 'nullable|exists:departments,id',
                'assigned_to' => 'nullable|exists:users,id',
                'priority' => ['required', Rule::in(['High', 'Medium', 'Low'])],
                'status' => ['nullable', Rule::in(['Pending', 'In Progress', 'Approval for Completed', 'Completed'])],
                'due_date' => 'nullable|date',
                'attachment' => 'nullable|file|max:10240'
            ]);

            if (isset($data['assigned_to']) && !$this->hasAdminRole($currentUser)) {
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

            $task = DB::transaction(function() use ($data, $attachment, $request, $currentUser) {
                $newTask = Task::create($data); 
                
                if ($attachment) {
                    $this->saveAttachment($newTask, $attachment, $currentUser->id);
                } 
                
                if (!empty($data['assigned_to'])) {
                    AppNotification::create([
                        'user_id' => $data['assigned_to'],
                        'task_id' => $newTask->id,
                        'title' => 'New Task Assigned',
                        'message' => 'You have been assigned: ' . $newTask->title,
                        'type' => 'task_assigned'
                    ]);

                    // Send Push Notification
                    app(FcmPushService::class)->sendToUser(
                        $data['assigned_to'],
                        'New Task Assigned',
                        'You have been assigned: ' . $newTask->title,
                        [
                            'task_id' => (string) $newTask->id, 
                            'type' => 'task_assigned',
                            'status' => (string) $newTask->status,
                            'due_date' => $newTask->due_date ? $newTask->due_date->format('Y-m-d') : ''
                        ]
                    );
                }

                return $newTask;
            });

            return response()->json([
                'message' => 'Task created and assigned successfully.',
                'task' => $this->payload($task->load(['department', 'assignee', 'creator', 'attachments']))
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        $currentUser = $request->user();

        if (!$this->hasAdminRole($currentUser)) {
            $isSubordinateTask = $currentUser->children()->where('id', $task->assigned_to)->exists();
            if ($task->created_by !== $currentUser->id && !$isSubordinateTask) {
                return response()->json(['message' => 'Unauthorized to update this task.'], 403);
            }
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => ['sometimes', Rule::in(['High', 'Medium', 'Low'])],
            'status' => [Rule::in(['Pending', 'In Progress', 'Approval for Completed', 'Completed'])],
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

                $freshTask = $task->fresh();

                // Send Push Notification
                app(FcmPushService::class)->sendToUser(
                    $data['assigned_to'],
                    'New Task Assigned',
                    'You have been assigned: ' . $freshTask->title,
                    [
                        'task_id' => (string) $freshTask->id, 
                        'type' => 'task_assigned',
                        'status' => (string) $freshTask->status,
                        'due_date' => $freshTask->due_date ? $freshTask->due_date->format('Y-m-d') : ''
                    ]
                );
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
            'status' => [Rule::in(['Pending', 'In Progress', 'Approval for Completed'])]
        ]);
        
        $data['completed_at'] = null;
        $task->update($data);
        
        if ($task->creator) {
            AppNotification::create([
                'user_id' => $task->creator->id,
                'task_id' => $task->id,
                'title' => 'Task Approval Requested',
                'message' => $request->user()->name . ' requested completion approval for "' . $task->title . '".',
                'type' => 'task_status'
            ]);

            $freshTask = $task->fresh();

            // Send Push Notification to Creator
            app(FcmPushService::class)->sendToUser(
                $task->creator->id,
                'Task Approval Requested',
                $request->user()->name . ' requested completion approval for "' . $freshTask->title . '".',
                [
                    'task_id' => (string) $freshTask->id, 
                    'type' => 'task_status',
                    'status' => (string) $freshTask->status,                           
                    'due_date' => $freshTask->due_date ? $freshTask->due_date->format('Y-m-d') : '' 
                ]
            );
        }

        return response()->json([
            'message' => 'Task status updated to approval requested.',
            'task' => $this->payload($task->fresh(['department', 'assignee']))
        ]);
    }

    public function destroy(Request $request, Task $task) 
    { 
        $currentUser = $request->user();
        if (!$this->hasAdminRole($currentUser) && $task->created_by !== $currentUser->id) {
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
        $roles = array_map('trim', explode(',', (string) $user->role));
        $hasAdminAccess = $this->hasAdminRole($user) || in_array('manager', $roles, true);
        $isTaskParticipant = $task->created_by === $user->id || $task->assigned_to === $user->id;

        abort_unless($hasAdminAccess || $isTaskParticipant, 403, 'You are not a participant in this task.');
    }

    private function hasAdminRole(User $user): bool
    {
        $roles = array_map('trim', explode(',', (string) $user->role));
        return count(array_intersect($roles, ['admin', 'super_admin'])) > 0;
    }

    private function payload(Task $t): array 
    { 
        return [
            'id' => $t->id,
            'title' => $t->title,
            'description' => $t->description,
            'status' => $t->status,
            'priority' => $t->priority ?? 'Medium',
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
                'comment' => $c->message,
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