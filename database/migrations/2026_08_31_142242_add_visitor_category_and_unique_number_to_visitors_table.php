<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('visitor_category', 50)
                ->default('lainnya');
        });

        DB::statement("
            CREATE UNIQUE INDEX visitors_employee_number_unique
            ON visitors (employee_number)
            WHERE employee_number IS NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            DROP INDEX visitors_employee_number_unique
            ON visitors
        ");

        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('visitor_category');
        });
    }
};