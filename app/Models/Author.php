<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $table = 'authors';

    protected $primaryKey = 'author_id';

    public $timestamps = false;

    protected $fillable = [
        'author_name',
        'pseudonym',
        'birth_date',
        'nationality',
        'biography',
        'website',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(
            Book::class,
            'book_authors',
            'author_id',
            'book_id',
            'author_id',
            'book_id'
        );
    }
    
}