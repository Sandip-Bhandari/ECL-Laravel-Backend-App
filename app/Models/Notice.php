<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $table = 'notices';

    protected $fillable = [
        'title',
        'message',
        'sender_id',
    ];

    /**
     * Optional: Relationship with User model (agar sender ki details chahiye ho)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}