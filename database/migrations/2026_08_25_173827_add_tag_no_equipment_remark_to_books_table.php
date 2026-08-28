<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('tag_no', 100)->nullable()->after('book_code');
            $table->string('equipment', 255)->nullable()->after('cat_no');
            $table->string('remark', 255)->nullable()->after('rack');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'tag_no',
                'equipment',
                'remark',
            ]);
        });
    }
};