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
        Schema::create('book_copies', function (Blueprint $table) {
            $table->bigInteger('copy_id')->identity();
            
            $table->integer('book_id');

            $table->string('copy_code', 50);

            $table->string('condition', 50)
                ->default('baik');

            $table->string('status', 50)
                ->default('tersedia');

            $table->text('notes')
                ->nullable();

            $table->foreign('book_id')
                ->references('book_id')
                ->on('books')
                ->onDelete('cascade');

            $table->unique('copy_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_copies');
    }
};