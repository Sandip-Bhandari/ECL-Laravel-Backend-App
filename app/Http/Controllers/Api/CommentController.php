<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
class CommentController extends Controller
{
    public function index(Request $request, Task $task) { if($request->user()->role==='employee' && $task->assigned_to!==$request->user()->id) abort(403); return response()->json($task->comments()->with('user:id,name')->latest()->get()->map(fn($c)=>['id'=>$c->id,'task_id'=>$c->task_id,'user_id'=>$c->user_id,'user_name'=>$c->user?->name,'comment'=>$c->comment,'created_at'=>$c->created_at?->toISOString()])); }
    public function store(Request $request, Task $task) { if($request->user()->role==='employee' && $task->assigned_to!==$request->user()->id) abort(403); $data=$request->validate(['comment'=>'required|string|max:5000']); $c=TaskComment::create(['task_id'=>$task->id,'user_id'=>$request->user()->id,'comment'=>$data['comment']]); return response()->json(['message'=>'Comment added.','comment'=>$c->load('user:id,name')],201); }
}
