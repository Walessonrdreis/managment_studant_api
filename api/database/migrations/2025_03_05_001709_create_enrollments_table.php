<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de matrículas.
     */
    public function up(): void
    {
        // Verifica se a tabela 'enrollments' já existe
        if (!Schema::hasTable('enrollments')) {
            // Cria a tabela 'enrollments' para armazenar informações de matrículas de estudantes em disciplinas
            Schema::create('enrollments', function (Blueprint $table) {
                $table->id(); // ID único da matrícula
                $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // ID da disciplina
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('enrollments', function (Blueprint $table) {
                if (!Schema::hasColumn('enrollments', 'student_id')) {
                    $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                }
                if (!Schema::hasColumn('enrollments', 'subject_id')) {
                    $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // ID da disciplina
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de matrículas.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
