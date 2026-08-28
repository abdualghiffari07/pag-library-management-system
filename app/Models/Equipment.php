<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $table = 'equipment';

    protected $primaryKey = 'equipment_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'equipment_name',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(
            Book::class,
            'equipment_id',
            'equipment_id'
        );
    }
}