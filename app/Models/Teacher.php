<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_name',
        'teacher_email',
        'teacher_position'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}