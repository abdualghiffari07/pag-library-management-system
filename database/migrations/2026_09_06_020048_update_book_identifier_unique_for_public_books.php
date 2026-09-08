<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus unique lama
        DB::statement("
            DROP INDEX books_book_identifier_unique
            ON books
        ");

        // ID Buku hanya unik untuk buku aktif
        DB::statement("
            CREATE UNIQUE INDEX books_book_identifier_public_unique
            ON books (book_identifier)
            WHERE status = 'public'
        ");
    }

    public function down(): void
    {
        DB::statement("
            DROP INDEX books_book_identifier_public_unique
            ON books
        ");

        DB::statement("
            CREATE UNIQUE INDEX books_book_identifier_unique
            ON books (book_identifier)
        ");
    }
};