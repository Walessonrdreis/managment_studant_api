<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = ['Admin', 'Teacher', 'Student'];

        foreach ($roles as $role) {
            // Verifica se o papel já existe antes de inserir
            if (!DB::table('roles')->where('name', $role)->exists()) {
                DB::table('roles')->insert(['name' => $role]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->whereIn('name', ['Admin', 'Teacher', 'Student'])->delete();
    }
};
