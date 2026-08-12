<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BookCopy;

class LoanDetail extends Model
{
    protected $table = 'loan_details';

    protected $primaryKey = 'loan_detail_id';

    public $timestamps = false;

    protected $fillable = [
        'loan_id',
        'book_id',
        'copy_id',
        'returned_date',
        'condition',
        'fine',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'returned_date' => 'date',
            'fine' => 'decimal:2',
        ];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(
            Loan::class,
            'loan_id',
            'loan_id'
        );
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(
            Book::class,
            'book_id',
            'book_id'
        );
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class, 'copy_id', 'copy_id');
    }
}