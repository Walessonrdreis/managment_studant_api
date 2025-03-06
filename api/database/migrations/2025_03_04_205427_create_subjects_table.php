<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de disciplinas.
     */
    public function up(): void
    {
        // Verifica se a tabela 'subjects' já existe
        if (!Schema::hasTable('subjects')) {
            // Cria a tabela 'subjects' para armazenar informações das disciplinas
            Schema::create('subjects', function (Blueprint $table) {
                $table->id(); // ID único da disciplina
                $table->string('name')->unique(); // Nome da disciplina
                $table->text('description')->nullable(); // Descrição da disciplina
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('subjects', function (Blueprint $table) {
                if (!Schema::hasColumn('subjects', 'name')) {
                    $table->string('name')->unique(); // Nome da disciplina
                }
                if (!Schema::hasColumn('subjects', 'description')) {
                    $table->text('description')->nullable(); // Descrição da disciplina
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de disciplinas.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
