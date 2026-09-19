<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visitor extends Model
{
    protected $table = 'visitors';

    protected $primaryKey = 'visitor_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'visitor_name',
        'employee_number',
        'visitor_category',
        'email',
        'phone_number',
        'profile_photo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Riwayat kunjungan
    public function checkins(): HasMany
    {
        return $this->hasMany(
            VisitorCheckin::class,
            'visitor_id',
            'visitor_id'
        );
    }

    // Akun login
    public function user(): HasOne
    {
        return $this->hasOne(
            User::class,
            'visitor_id',
            'visitor_id'
        );
    }
}