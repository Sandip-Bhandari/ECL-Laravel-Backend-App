<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'parent_id', 
        'name',
        'email',
        'password',
        'phone',
        'department', 
        'status',
        'role',
        'is_dual_role',
        'designation',
        'branch',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- RELATIONSHIPS ---

    
    // 1. Department relation (Explicitly foreign key 'department' specify kar rahe hain)
    public function department()
    {
        return $this->belongsTo(Department::class, 'department', 'id');
    }

    // 2. Is user ka boss / parent kaun hai
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // 3. Is user ke under direct kaun-kaun se employees/subordinates hain
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // 4. Alias for children (Task assignment code ko aur readable banane ke liye)
    public function subordinates()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
}