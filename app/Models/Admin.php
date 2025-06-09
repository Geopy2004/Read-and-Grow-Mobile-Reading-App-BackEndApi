<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'admin_email',
        'admin_security_code',
    ];

    protected $hidden = [
        'admin_security_code',
    ];

    // Relationship: Admin belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to check admin role if needed
    public function isAdmin()
    {
        return $this->user && $this->user->role === 'admin';
    }
}
