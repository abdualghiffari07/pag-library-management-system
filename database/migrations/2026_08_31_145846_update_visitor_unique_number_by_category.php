<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            DROP INDEX visitors_employee_number_unique
            ON visitors
        ");

        DB::statement("
            CREATE UNIQUE INDEX visitors_category_number_unique
            ON visitors (visitor_category, employee_number)
        ");
    }

    public function down(): void
    {
        DB::statement("
            DROP INDEX visitors_category_number_unique
            ON visitors
        ");

        DB::statement("
            CREATE UNIQUE INDEX visitors_employee_number_unique
            ON visitors (employee_number)
        ");
    }
};