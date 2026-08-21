<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    protected $table = 'book_copies';

    protected $primaryKey = 'copy_id';

    public $timestamps = false;

    protected $fillable = [
        'book_id',
        'copy_code',
        'condition',
        'status',
        'notes',
    ];

    public function book()
    {
        return $this->belongsTo(
            Book::class,
            'book_id',
            'book_id'
        );
    }
}