<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loans';

    protected $primaryKey = 'loan_id';

    /**
     * Tabel loans tidak menggunakan
     * created_at dan updated_at Laravel.
     */
    public $timestamps = false;

    protected $fillable = [
        'borrower_name',
        'nopek',
        'loan_date',
        'due_date',
        'returned_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'returned_date' => 'date',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }

    /**
     * Relasi ke detail peminjaman
     */
    public function loanDetails()
    {
        return $this->hasMany(
            LoanDetail::class,
            'loan_id',
            'loan_id'
        );
    }
}