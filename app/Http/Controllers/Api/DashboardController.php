<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $base=Task::query();
        $stats=['total'=>$base->count(),'pending'=>(clone $base)->where('status','Pending')->count(),'in_progress'=>(clone $base)->where('status','In Progress')->count(),'completed'=>(clone $base)->where('status','Completed')->count(),'overdue'=>(clone $base)->where('status','!=','Completed')->whereNotNull('due_date')->whereDate('due_date','<',today())->count()];
        $byDept=Department::where('is_active',true)->get()->map(fn($d)=>['department_id'=>$d->id,'department'=>$d->name,'count'=>Task::where('department_id',$d->id)->count()]);
        $discussions=TaskComment::with(['user:id,name,department_id','task:id,title,department_id'])->latest()->limit(10)->get()->map(fn($c)=>['id'=>$c->id,'user'=>$c->user?->name,'department'=>$c->user?->department?->name,'task'=>$c->task?->title,'comment'=>$c->comment,'created_at'=>$c->created_at?->toISOString()]);
        return response()->json(['stats'=>$stats,'tasks_by_department'=>$byDept,'employee_discussions'=>$discussions,'active_workforce'=>User::where('status','active')->count()]);
    }
}
