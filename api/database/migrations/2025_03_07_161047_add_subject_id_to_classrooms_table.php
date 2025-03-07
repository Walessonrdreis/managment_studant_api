<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectIdToClassroomsTable extends Migration
{
    /**
     * Executa as migrações para adicionar a coluna subject_id à tabela classrooms.
     */
    public function up(): void
    {
        // Verifica se a tabela 'classrooms' existe antes de tentar adicionar a coluna
        if (Schema::hasTable('classrooms')) {
            Schema::table('classrooms', function (Blueprint $table) {
                // Verifica se a coluna já existe antes de adicioná-la
                if (!Schema::hasColumn('classrooms', 'subject_id')) {
                    $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade')->after('id'); // Adiciona a coluna subject_id
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a coluna subject_id da tabela classrooms.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
}
