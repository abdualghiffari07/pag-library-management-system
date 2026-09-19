<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $visitorIdType = Schema::getColumnType(
            'visitors',
            'visitor_id'
        );

        Schema::table('users', function (Blueprint $table) use ($visitorIdType) {
            if (in_array($visitorIdType, ['bigint', 'bigInteger'], true)) {
                $table->bigInteger('visitor_id')->nullable();
            } else {
                $table->integer('visitor_id')->nullable();
            }

            $table->boolean('must_change_password')
                ->default(false);

            $table->index(
                'visitor_id',
                'users_visitor_id_index'
            );
        });

        $roleExists = DB::table('roles')
            ->where('role_name', 'pekerja')
            ->exists();

        if (!$roleExists) {
            DB::table('roles')->insert([
                'role_name' => 'pekerja',
                'created_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(
                'users_visitor_id_index'
            );

            $table->dropColumn([
                'visitor_id',
                'must_change_password',
            ]);
        });

        $workerRole = DB::table('roles')
            ->where('role_name', 'pekerja')
            ->first();

        if (
            $workerRole
            && !DB::table('users')
                ->where('role_id', $workerRole->role_id)
                ->exists()
        ) {
            DB::table('roles')
                ->where('role_id', $workerRole->role_id)
                ->delete();
        }
    }
};