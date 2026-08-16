<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('pseudonym', 255)
                ->nullable()
                ->after('author_name');

            $table->date('birth_date')
                ->nullable()
                ->after('pseudonym');

            $table->string('nationality', 100)
                ->nullable()
                ->after('birth_date');

            $table->text('biography')
                ->nullable()
                ->after('nationality');

            $table->string('website', 500)
                ->nullable()
                ->after('biography');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn([
                'pseudonym',
                'birth_date',
                'nationality',
                'biography',
                'website',
            ]);
        });
    }
};