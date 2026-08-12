<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('book_copies');

        DB::statement("
            CREATE TABLE book_copies (
                copy_id BIGINT IDENTITY(1,1) NOT NULL,
                book_id INT NOT NULL,
                copy_code NVARCHAR(50) NOT NULL,
                condition NVARCHAR(50) NOT NULL DEFAULT 'baik',
                status NVARCHAR(50) NOT NULL DEFAULT 'tersedia',
                notes NVARCHAR(MAX) NULL,

                CONSTRAINT PK_book_copies
                    PRIMARY KEY (copy_id),

                CONSTRAINT UQ_book_copies_copy_code
                    UNIQUE (copy_code),

                CONSTRAINT FK_book_copies_books
                    FOREIGN KEY (book_id)
                    REFERENCES books(book_id)
                    ON DELETE CASCADE
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_copies');
    }
};