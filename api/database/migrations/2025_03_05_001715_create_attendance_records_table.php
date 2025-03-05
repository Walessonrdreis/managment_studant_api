<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de registros de presença.
     */
    public function up(): void
    {
        // Verifica se a tabela 'attendance_records' já existe
        if (!Schema::hasTable('attendance_records')) {
            // Cria a tabela 'attendance_records' para armazenar registros de presença dos estudantes
            Schema::create('attendance_records', function (Blueprint $table) {
                $table->id(); // ID único do registro de presença
                $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                $table->foreignId('classroom_id')->constrained()->onDelete('cascade'); // ID da sala de aula
                $table->date('date'); // Data do registro de presença
                $table->enum('status', ['present', 'absent'])->nullable(); // Status de presença
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('attendance_records', function (Blueprint $table) {
                if (!Schema::hasColumn('attendance_records', 'student_id')) {
                    $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                }
                if (!Schema::hasColumn('attendance_records', 'classroom_id')) {
                    $table->foreignId('classroom_id')->constrained()->onDelete('cascade'); // ID da sala de aula
                }
                if (!Schema::hasColumn('attendance_records', 'date')) {
                    $table->date('date'); // Data do registro de presença
                }
                if (!Schema::hasColumn('attendance_records', 'status')) {
                    $table->enum('status', ['present', 'absent'])->nullable(); // Status de presença
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de registros de presença.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
