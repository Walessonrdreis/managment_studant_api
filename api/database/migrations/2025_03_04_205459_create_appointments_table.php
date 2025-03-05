<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de agendamentos.
     */
    public function up(): void
    {
        // Verifica se a tabela 'appointments' já existe
        if (!Schema::hasTable('appointments')) {
            // Cria a tabela 'appointments' para armazenar agendamentos entre estudantes e professores
            Schema::create('appointments', function (Blueprint $table) {
                $table->id(); // ID único do agendamento
                $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                $table->foreignId('teacher_id')->constrained()->onDelete('cascade'); // ID do professor
                $table->dateTime('date')->nullable(); // Data e hora do agendamento
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('appointments', function (Blueprint $table) {
                if (!Schema::hasColumn('appointments', 'student_id')) {
                    $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                }
                if (!Schema::hasColumn('appointments', 'teacher_id')) {
                    $table->foreignId('teacher_id')->constrained()->onDelete('cascade'); // ID do professor
                }
                if (!Schema::hasColumn('appointments', 'date')) {
                    $table->dateTime('date')->nullable(); // Data e hora do agendamento
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de agendamentos.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
