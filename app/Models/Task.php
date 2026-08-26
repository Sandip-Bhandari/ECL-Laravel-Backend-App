<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'department_id',
        'assigned_to',
        'created_by',
        'status',
        'priority', // 🟢 Yeh field add karna zaroori hai
        'due_date',
        'completed_at'
    ];

    protected $casts = [
        'due_date' => 'date:Y-m-d',
        'completed_at' => 'datetime'
    ];

    public function department(): BelongsTo 
    { 
        return $this->belongsTo(Department::class); 
    }

    public function assignee(): BelongsTo 
    { 
        return $this->belongsTo(User::class, 'assigned_to'); 
    }

    public function creator(): BelongsTo 
    { 
        return $this->belongsTo(User::class, 'created_by'); 
    }

    public function comments() 
    { 
        return $this->hasMany(TaskComment::class); 
    }

    public function attachments() 
    { 
        return $this->hasMany(TaskAttachment::class); 
    }

    public function getIsOverdueAttribute(): bool 
    { 
        return $this->status !== 'Completed' && $this->due_date?->isBefore(today()); 
    }
}