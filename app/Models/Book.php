<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'books';

    protected $primaryKey = 'book_id';

    public $timestamps = false;

    protected $fillable = [
        'book_identifier',
        'tag_no',
        'book_code',
        'cat_no',
        'equipment_id',
        'title',
        'rack',
        'remark',
        'publisher',
        'description',
        'status',
    ];

    // Lokasi buku
    public function location(): BelongsTo
    {
        return $this->belongsTo(
            Location::class,
            'location_id',
            'location_id'
        );
    }

    // Equipment buku
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(
            Equipment::class,
            'equipment_id',
            'equipment_id'
        );
    }

    // Author buku
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

    // Kategori buku
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

    // Detail peminjaman
    public function loanDetails(): HasMany
    {
        return $this->hasMany(
            LoanDetail::class,
            'book_id',
            'book_id'
        );
    }

    // Eksemplar buku
    public function copies(): HasMany
    {
        return $this->hasMany(
            BookCopy::class,
            'book_id',
            'book_id'
        );
    }
}