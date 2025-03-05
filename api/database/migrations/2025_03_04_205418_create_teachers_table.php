<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de professores.
     */
    public function up(): void
    {
        // Verifica se a tabela 'teachers' já existe
        if (!Schema::hasTable('teachers')) {
            // Cria a tabela 'teachers' para armazenar informações dos professores
            Schema::create('teachers', function (Blueprint $table) {
                $table->id(); // ID único do professor
                $table->string('name')->nullable(); // Nome do professor
                $table->string('subject')->nullable(); // Disciplina que o professor leciona
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('teachers', function (Blueprint $table) {
                if (!Schema::hasColumn('teachers', 'name')) {
                    $table->string('name')->nullable(); // Nome do professor
                }
                if (!Schema::hasColumn('teachers', 'subject')) {
                    $table->string('subject')->nullable(); // Disciplina que o professor leciona
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de professores.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
