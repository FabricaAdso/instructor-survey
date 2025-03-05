<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'identity_document',
        'name',
        'last_name',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function apprentice ()
    {
        return $this->hasOne (Apprentice::class, 'user_id');
    }

    public function instructors ()
    {
        return $this->hasOne(Instructor::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

}
