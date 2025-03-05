<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de presenças.
     */
    public function up(): void
    {
        // Verifica se a tabela 'attendances' já existe
        if (!Schema::hasTable('attendances')) {
            // Cria a tabela 'attendances' para armazenar informações de presença dos estudantes
            Schema::create('attendances', function (Blueprint $table) {
                $table->id(); // ID único da presença
                $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                $table->date('date')->nullable(); // Data da presença
                $table->enum('status', ['present', 'absent'])->nullable(); // Status de presença
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'student_id')) {
                    $table->foreignId('student_id')->constrained()->onDelete('cascade'); // ID do estudante
                }
                if (!Schema::hasColumn('attendances', 'date')) {
                    $table->date('date')->nullable(); // Data da presença
                }
                if (!Schema::hasColumn('attendances', 'status')) {
                    $table->enum('status', ['present', 'absent'])->nullable(); // Status de presença
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de presenças.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
