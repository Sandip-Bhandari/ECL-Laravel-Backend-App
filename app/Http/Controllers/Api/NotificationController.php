<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\DeviceToken;
use App\Models\Task;
use App\Services\FcmPushService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            AppNotification::where('user_id', $request->user()->id)->latest()->limit(100)->get()
        );
    }

    public function markRead(Request $request, AppNotification $notification)
    {
        abort_unless($notification->user_id === $request->user()->id, 403);
        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function destroy(Request $request, AppNotification $notification)
    {
        abort_unless($notification->user_id === $request->user()->id, 403);
        $notification->delete();

        return response()->json(['message' => 'Notification deleted.']);
    }

    public function storeDeviceToken(Request $request)
    {
        $data = $request->validate([
            'device_token' => 'required|string',
            'platform' => ['required', Rule::in(['android', 'ios', 'other'])],
        ]);
        $token = DeviceToken::updateOrCreate(
            ['device_token' => $data['device_token']],
            [
                'user_id' => $request->user()->id,
                'platform' => $data['platform'],
                'last_seen_at' => now(),
            ]
        );

        return response()->json(['message' => 'Device token saved.', 'id' => $token->id]);
    }

    public function sendDeadlineAlerts(): void
    {
        $tasks = Task::where('status', '!=', 'Completed')
            ->whereNotNull('assigned_to')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', today())
            ->with('assignee')
            ->get();

        foreach ($tasks as $task) {
            if (!$task->assignee) {
                continue;
            }

            $type = $task->due_date->isBefore(today()) ? 'overdue' : 'deadline';
            $exists = AppNotification::where('user_id', $task->assigned_to)
                ->where('task_id', $task->id)
                ->where('type', $type)
                ->whereDate('created_at', today())
                ->exists();
            if ($exists) {
                continue;
            }

            $title = $type === 'overdue' ? 'Critical Task Alert' : 'Task Deadline Reminder';
            $message = $type === 'overdue'
                ? $task->title . ' is overdue. Please update the task.'
                : $task->title . ' is due today.';
            AppNotification::create([
                'user_id' => $task->assigned_to,
                'task_id' => $task->id,
                'title' => $title,
                'message' => $message,
                'type' => $type,
            ]);
            app(FcmPushService::class)->sendToUser($task->assigned_to, $title, $message, [
                'task_id' => (string) $task->id,
                'type' => $type,
            ]);
        }
    }
}
