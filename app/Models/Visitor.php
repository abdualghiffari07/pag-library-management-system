<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    protected $table = 'visitors';

    protected $primaryKey = 'visitor_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'visitor_category',
        'visitor_name',
        'employee_number',
        'phone_number',
        'profile_photo',
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
}