<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah ID Buku
        Schema::table('books', function (Blueprint $table) {
            $table->string('book_identifier', 100)->nullable();
        });

        // Isi ID untuk buku lama
        DB::statement("
            UPDATE books
            SET book_identifier =
                'BOOK-' + CAST(book_id AS VARCHAR(20))
            WHERE book_identifier IS NULL
        ");

        // Jadikan wajib diisi
        DB::statement("
            ALTER TABLE books
            ALTER COLUMN book_identifier NVARCHAR(100) NOT NULL
        ");

        // ID Buku harus unik
        Schema::table('books', function (Blueprint $table) {
            $table->unique(
                'book_identifier',
                'books_book_identifier_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(
                'books_book_identifier_unique'
            );
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('book_identifier');
        });
    }
};