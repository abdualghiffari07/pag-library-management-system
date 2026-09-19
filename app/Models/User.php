<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'visitor_id',
        'nopek',
        'name',
        'email',
        'password_hash',
        'function_name',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'must_change_password' => 'boolean',
    ];

    // Kolom password autentikasi
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    // Password autentikasi
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // Role
    public function role(): BelongsTo
    {
        return $this->belongsTo(
            Role::class,
            'role_id',
            'role_id'
        );
    }

    // Data pengunjung
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(
            Visitor::class,
            'visitor_id',
            'visitor_id'
        );
    }
}