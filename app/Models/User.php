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
        'name',
        'email',
        'password',
        'phone',
        'designation',
        'department_id', // String wali 'department' ki jagah foreign key ID
        'branch',
        'role',
        'status',
        'parent_id'
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

    // 1. Department relation (Har user kisi ek department se belong karega)
    public function department()
    {
        return $this->belongsTo(Department::class);
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