<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de notas.
     */
    public function up(): void
    {
        // Verifica se a tabela 'grades' já existe
        if (!Schema::hasTable('grades')) {
            // Cria a tabela 'grades' para armazenar informações de notas dos estudantes
            Schema::create('grades', function (Blueprint $table) {
                $table->id(); // ID único da nota
                $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // ID da disciplina
                $table->decimal('grade', 5, 2); // Nota com duas casas decimais
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('grades', function (Blueprint $table) {
                if (!Schema::hasColumn('grades', 'student_id')) {
                    $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                }
                if (!Schema::hasColumn('grades', 'subject_id')) {
                    $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // ID da disciplina
                }
                if (!Schema::hasColumn('grades', 'grade')) {
                    $table->decimal('grade', 5, 2); // Nota com duas casas decimais
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de notas.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
