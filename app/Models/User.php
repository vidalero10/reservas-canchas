<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROL_CLIENTE = 'cliente';
    public const ROL_ADMIN = 'admin';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }

    public function esAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMIN;
    }

    public function esCliente(): bool
    {
        return $this->rol === self::ROL_CLIENTE;
    }
}
