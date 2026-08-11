<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    protected $table = 'loans';

    protected $primaryKey = 'loan_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'loan_date',
        'due_date',
        'returned_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'returned_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }

    public function loanDetails(): HasMany
    {
        return $this->hasMany(
            LoanDetail::class,
            'loan_id',
            'loan_id'
        );
    }
}