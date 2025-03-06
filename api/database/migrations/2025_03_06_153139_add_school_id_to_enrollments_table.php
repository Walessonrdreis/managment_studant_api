<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolIdToEnrollmentsTable extends Migration
{
    /**
     * Executa as migrações para adicionar a coluna school_id à tabela enrollments.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->constrained('schools')->after('subject_id');
        });
    }

    /**
     * Reverte as migrações, removendo a coluna school_id da tabela enrollments.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
}
