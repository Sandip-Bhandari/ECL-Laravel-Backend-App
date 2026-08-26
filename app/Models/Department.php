<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'code', 'is_active', 'parent_id'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Users relation
    public function users() 
    { 
        return $this->hasMany(User::class); 
    }

    // Tasks relation
    public function tasks() 
    { 
        return $this->hasMany(Task::class); 
    }

    // Parent Department (Sub-department ka main department)
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    // Child Departments (Direct sub-departments)
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    // Recursive Children (Infinite depth tak poori department chain/tree nikalne ke liye)
    public function recursiveChildren()
    {
        return $this->children()->with('recursiveChildren');
    }
}