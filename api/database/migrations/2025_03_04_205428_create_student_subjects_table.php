<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de disciplinas dos estudantes.
     */
    public function up(): void
    {
        // Verifica se a tabela 'student_subjects' já existe
        if (!Schema::hasTable('student_subjects')) {
            // Cria a tabela 'student_subjects' para relacionar estudantes e disciplinas
            Schema::create('student_subjects', function (Blueprint $table) {
                $table->id(); // ID único do relacionamento
                $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // ID da disciplina
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('student_subjects', function (Blueprint $table) {
                if (!Schema::hasColumn('student_subjects', 'student_id')) {
                    $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                }
                if (!Schema::hasColumn('student_subjects', 'subject_id')) {
                    $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // ID da disciplina
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de disciplinas dos estudantes.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subjects');
    }
};
