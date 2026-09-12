<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_checkins', function (Blueprint $table) {
            $table->id('checkin_id');

            $table->foreignId('visitor_id')
                ->constrained('visitors', 'visitor_id');

            $table->string('selfie_path', 255);
            $table->timestamp('checked_in_at');
            $table->timestamps();

            $table->index(['visitor_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_checkins');
    }
};