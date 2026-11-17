<?php

namespace App\Http\Controllers\Api;

use App\Models\AppNotification;
use App\Models\Task;
use App\Services\FcmPushService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class ChatController extends Controller
{
    public function index(Request $request, Task $task)
    {
        $this->authorizeParticipant($request, $task);

        $messages = Message::with('user:id,name')
            ->where('task_id', $task->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $messages
        ]);
    }

    public function store(Request $request, Task $task)
    {
        $this->authorizeParticipant($request, $task);
        $request->merge([
            'message' => $request->input('message')
                ?? $request->input('comment')
                ?? $request->input('body')
                ?? $request->input('text'),
        ]);
        $data = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'message' => trim($data['message']),
        ])->load('user:id,name');

        $otherParticipantId = $request->user()->id === $task->assigned_to
            ? $task->created_by
            : $task->assigned_to;

        if ($otherParticipantId && $otherParticipantId !== $request->user()->id) {
            $title = 'New task discussion message';
            $body = $request->user()->name . ' sent a message about "' . $task->title . '".';

            AppNotification::create([
                'user_id' => $otherParticipantId,
                'task_id' => $task->id,
                'title' => $title,
                'message' => $body,
                'type' => 'task_message',
            ]);

            app(FcmPushService::class)->sendToUser($otherParticipantId, $title, $body, [
                'task_id' => (string) $task->id,
                'type' => 'task_message',
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $message,
        ], 201);
    }

    private function authorizeParticipant(Request $request, Task $task): void
    {
        $user = $request->user();
        $roles = array_map('trim', explode(',', (string) $user->role));
        $hasAdminAccess = count(array_intersect($roles, ['admin', 'super_admin', 'manager'])) > 0;
        $isTaskParticipant = $task->created_by === $user->id || $task->assigned_to === $user->id;

        abort_unless($hasAdminAccess || $isTaskParticipant, 403, 'You are not a participant in this task.');
    }
}