<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de salas de aula.
     */
    public function up(): void
    {
        // Verifica se a tabela 'classrooms' já existe
        if (!Schema::hasTable('classrooms')) {
            // Cria a tabela 'classrooms' para armazenar informações das salas de aula
            Schema::create('classrooms', function (Blueprint $table) {
                $table->id(); // ID único da sala de aula
                $table->string('name')->unique(); // Nome da sala de aula
                $table->integer('capacity')->nullable(); // Capacidade da sala
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('classrooms', function (Blueprint $table) {
                if (!Schema::hasColumn('classrooms', 'name')) {
                    $table->string('name')->unique(); // Nome da sala de aula
                }
                if (!Schema::hasColumn('classrooms', 'capacity')) {
                    $table->integer('capacity')->nullable(); // Capacidade da sala
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de salas de aula.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
