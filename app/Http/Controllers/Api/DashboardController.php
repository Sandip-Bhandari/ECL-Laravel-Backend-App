<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $base = Task::query();
        $this->applyVisibilityScope($base, $user);

        $stats = [
            'total' => (clone $base)->count(),
            'pending' => (clone $base)->where('status', 'Pending')->count(),
            'in_progress' => (clone $base)->where('status', 'In Progress')->count(),
            'completed' => (clone $base)->where('status', 'Completed')->count(),
            'overdue' => (clone $base)->where('status', '!=', 'Completed')
                ->whereNotNull('due_date')->whereDate('due_date', '<', today())->count(),
        ];

        $byDept = Department::where('is_active', true)->get()->map(function ($department) use ($user) {
            $tasks = Task::where('department_id', $department->id);
            $this->applyVisibilityScope($tasks, $user);

            return [
                'department_id' => $department->id,
                'department' => $department->name,
                'count' => $tasks->count(),
            ];
        });

        $discussions = TaskComment::with(['user:id,name,department_id', 'task:id,title,department_id'])
            ->whereHas('task', fn (Builder $query) => $this->applyVisibilityScope($query, $user))
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($comment) => [
                'id' => $comment->id,
                'user' => $comment->user?->name,
                'department' => $comment->user?->department?->name,
                'task' => $comment->task?->title,
                'comment' => $comment->message,
                'created_at' => $comment->created_at?->toISOString(),
            ]);

        $workforce = User::where('status', 'active');
        if (!$this->hasAdminRole($user)) {
            $workforce->whereIn('id', $this->visibleUserIds($user));
        }

        return response()->json([
            'stats' => $stats,
            'tasks_by_department' => $byDept,
            'employee_discussions' => $discussions,
            'active_workforce' => $workforce->count(),
        ]);
    }

    private function applyVisibilityScope(Builder $query, User $user): void
    {
        if ($this->hasAdminRole($user)) {
            return;
        }

        $userIds = $this->visibleUserIds($user);
        $query->where(function (Builder $query) use ($userIds) {
            $query->whereIn('created_by', $userIds)
                ->orWhereIn('assigned_to', $userIds);
        });
    }

    private function visibleUserIds(User $user): array
    {
        $roles = array_map('trim', explode(',', (string) $user->role));
        if (in_array('manager', $roles, true)) {
            return $user->children()->pluck('id')->push($user->id)->all();
        }

        return [$user->id];
    }

    private function hasAdminRole(User $user): bool
    {
        $roles = array_map('trim', explode(',', (string) $user->role));
        return count(array_intersect($roles, ['admin', 'super_admin'])) > 0;
    }
}
