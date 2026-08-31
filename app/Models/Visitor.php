<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];
}