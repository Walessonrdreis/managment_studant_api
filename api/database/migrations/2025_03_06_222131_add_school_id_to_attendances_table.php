<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolIdToAttendancesTable extends Migration
{
    /**
     * Executa as migrações para adicionar a coluna school_id à tabela attendances.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->constrained('schools')->after('status');
        });
    }

    /**
     * Reverte as migrações, removendo a coluna school_id da tabela attendances.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
}
