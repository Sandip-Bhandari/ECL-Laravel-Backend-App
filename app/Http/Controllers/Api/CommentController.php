<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskComment;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    // Task ke saare comments/messages fetch karne ke liye
    public function index($taskId)
    {
        $comments = TaskComment::where('task_id', $taskId)
            ->with('user:id,name') // User ka naam sath me lane ke liye
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $comments
        ]);
    }

    // Naya comment/message save karne ke liye
    public function store(Request $request, $taskId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $user = $request->user();

        $comment = TaskComment::create([
            'id' => (string) Str::uuid(), // Custom/String ID handle karne ke liye
            'task_id' => $taskId,
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'status' => true,
            'data' => $comment
        ], 201);
    }
}