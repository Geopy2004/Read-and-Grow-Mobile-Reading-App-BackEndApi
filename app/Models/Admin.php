<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'admin_email',
        'admin_security_code',
    ];

    protected $hidden = [
        'admin_security_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isAdmin()
    {
        return $this->user && $this->user->role === 'admin';
    }
}
