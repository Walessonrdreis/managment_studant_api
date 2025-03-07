<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToSchoolsTable extends Migration
{
    /**
     * Executa as migrações para adicionar a coluna logo à tabela schools.
     */
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('name'); // Logo da escola
        });
    }

    /**
     * Reverte as migrações, removendo a coluna logo da tabela schools.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
}
