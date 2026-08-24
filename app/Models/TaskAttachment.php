<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TaskAttachment extends Model
{
    protected $fillable = ['task_id','uploaded_by','file_name','file_path','mime_type','file_size'];
    protected $appends = ['url'];
    public function task() { return $this->belongsTo(Task::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
    public function getUrlAttribute(): string { return asset('storage/'.$this->file_path); }
}
