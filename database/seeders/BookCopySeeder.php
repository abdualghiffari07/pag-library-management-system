<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Database\Seeder;

class BookCopySeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::orderBy('book_id')->get();

        foreach ($books as $book) {

            // Buat 3 eksemplar untuk setiap buku
            for ($i = 1; $i <= 3; $i++) {

                $prefix = match ($book->book_id) {
                    1 => 'BK-CLN',
                    2 => 'BK-REF',
                    3 => 'BK-DB',
                    4 => 'BK-NET',
                    5 => 'BK-OS',
                    6 => 'BK-ALG',
                    7 => 'BK-SE',
                    8 => 'BK-HML',
                    9 => 'BK-PDA',
                    default => 'BK-BOOK',
                };

                BookCopy::firstOrCreate(
                    [
                        'book_id' => $book->book_id,
                        'copy_code' => sprintf('%s-%03d', $prefix, $i),
                    ],
                    [
                        'condition' => 'baik',
                        'status' => 'tersedia',
                        'notes' => null,
                    ]
                );
            }
        }
    }
}