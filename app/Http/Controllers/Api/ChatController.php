<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($taskId)
    {
        $messages = Message::with('user')
            ->where('task_id', $taskId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $messages
        ], 200);
    }

   public function store(Request $request, $taskId)
{
    \Log::info('Incoming Chat Request for Task: ' . $taskId, $request->all());

    // Flexible key handling (agar app se 'message' ya 'comment' kuch bhi aaye)
    $msgText = $request->input('message') ?? $request->input('comment') ?? $request->input('body');

    if (empty($msgText)) {
        return response()->json([
            'status' => false,
            'message' => 'Message field is required.'
        ], 422);
    }

    $message = Message::create([
        'task_id' => $taskId,
        'user_id' => auth()->id() ?? 1, 
        'message' => $msgText,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Message saved successfully',
        'data' => $message->load('user')
    ], 201);
}
}