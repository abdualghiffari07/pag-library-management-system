<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'returned_date' => 'date',
        'fine' => 'decimal:2',
    ];

    /**
     * Relasi ke peminjaman
     */
    public function loan()
    {
        return $this->belongsTo(
            Loan::class,
            'loan_id',
            'loan_id'
        );
    }

    /**
     * Relasi ke buku
     */
    public function book()
    {
        return $this->belongsTo(
            Book::class,
            'book_id',
            'book_id'
        );
    }

    /**
     * Relasi ke eksemplar buku
     */
    public function bookCopy()
    {
        return $this->belongsTo(
            BookCopy::class,
            'copy_id',
            'copy_id'
        );
    }
}