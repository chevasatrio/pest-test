<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function isManager(): bool
    {
        if ($this->role === 'manager') {
            return true;
        }
        return false;
    }

    public function isKaryawan(): bool
    {
        if ($this->role === 'karyawan') {
            return true;
        }
        return false;
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }
}
