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
        // Verifica se os papéis já existem antes de inserir
        if (DB::table('roles')->whereIn('name', ['Admin', 'Teacher', 'Student'])->count() === 0) {
            DB::table('roles')->insert([
                ['name' => 'Admin'],
                ['name' => 'Teacher'],
                ['name' => 'Student'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove os papéis caso a migration seja revertida
        DB::table('roles')->whereIn('name', ['Admin', 'Teacher', 'Student'])->delete();
    }
};
