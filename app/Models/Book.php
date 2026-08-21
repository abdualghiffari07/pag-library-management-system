<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'books';

    protected $primaryKey = 'book_id';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'origin',
        'cover',
        'publication_year',
        'rack',
        'status',
        'description',
        'book_code',
        'cat_no',
        'publisher',
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(
            Author::class,
            'book_authors',
            'book_id',
            'author_id',
            'book_id',
            'author_id'
        );
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'book_categories',
            'book_id',
            'category_id',
            'book_id',
            'category_id'
        );
    }

    public function loanDetails(): HasMany
    {
        return $this->hasMany(
            LoanDetail::class,
            'book_id',
            'book_id'
        );
    }

    public function copies(): HasMany
    {
        return $this->hasMany(
            BookCopy::class,
            'book_id',
            'book_id'
        );
    }
}