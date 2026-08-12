<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\LoanDetail;

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

    public function book(): BelongsTo
    {
        return $this->belongsTo(
            Book::class,
            'book_id',
            'book_id'
        );
    }

    public function loanDetails(): HasMany
    {
        return $this->hasMany(
            LoanDetail::class,
            'copy_id',
            'copy_id'
        );
    }

    
}