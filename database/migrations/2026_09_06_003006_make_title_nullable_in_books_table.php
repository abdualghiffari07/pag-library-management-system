<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Title boleh kosong
        DB::statement("
            ALTER TABLE books
            ALTER COLUMN title VARCHAR(255) NULL
        ");
    }

    public function down(): void
    {
        // Hindari NULL saat rollback
        DB::statement("
            UPDATE books
            SET title = ''
            WHERE title IS NULL
        ");

        DB::statement("
            ALTER TABLE books
            ALTER COLUMN title VARCHAR(255) NOT NULL
        ");
    }
};