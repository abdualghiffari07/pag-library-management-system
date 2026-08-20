<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'role_id',
        'nopek',
        'name',
        'email',
        'password_hash',
        'function_name',
        'is_active',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Password authentication menggunakan password_hash
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Relasi User -> Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}