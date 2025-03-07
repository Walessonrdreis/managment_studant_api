<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentIdToClassroomsTable extends Migration
{
    /**
     * Executa as migrações para adicionar a coluna student_id à tabela classrooms.
     */
    public function up(): void
    {
        // Verifica se a tabela 'classrooms' existe antes de tentar adicionar a coluna
        if (Schema::hasTable('classrooms')) {
            Schema::table('classrooms', function (Blueprint $table) {
                // Verifica se a coluna já existe antes de adicioná-la
                if (!Schema::hasColumn('classrooms', 'student_id')) {
                    $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->after('id'); // Adiciona a coluna student_id
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a coluna student_id da tabela classrooms.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
        });
    }
}
