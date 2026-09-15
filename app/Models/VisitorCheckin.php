<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorCheckin extends Model
{
    protected $table = 'visitor_checkins';

    protected $primaryKey = 'checkin_id';

    protected $fillable = [
        'visitor_id',
        'selfie_path',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(
            Visitor::class,
            'visitor_id',
            'visitor_id'
        );
    }
}