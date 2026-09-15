<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message; // <-- Yeh ensure karein ki yahi model import ho

class CommentController extends Controller
{
    // Task ke saare messages fetch karne ke liye (GET API)
    public function index($taskId)
    {
        $messages = Message::where('task_id', $taskId)
            ->with('user:id,name') 
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $messages
        ]);
    }

    // Naya message save karne ke liye (POST API)
    public function store(Request $request, $taskId)
    {
        $messageText = $request->input('message') 
            ?? $request->input('comment') 
            ?? $request->input('body') 
            ?? $request->input('text');

        if (empty($messageText)) {
            return response()->json([
                'status' => false,
                'message' => 'Message text is empty or missing.'
            ], 422);
        }

        $user = $request->user();

        // 'messages' table mein data save hoga
        $message = Message::create([
            'task_id' => $taskId,
            'user_id' => $user->id,
            'message' => $messageText, 
        ]);

        $message->load('user:id,name');

        return response()->json([
            'status' => true,
            'data' => $message
        ], 201);
    }
}