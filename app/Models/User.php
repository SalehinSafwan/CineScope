<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; // since you used user_id instead of id

    protected $hidden = [
        'password_hash',
    ];

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'role',
    ];

    // this tells Laravel which column stores the real password hash
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }
}

