<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de estudantes.
     */
    public function up(): void
    {
        // Verifica se a tabela 'students' já existe
        if (!Schema::hasTable('students')) {
            // Cria a tabela 'students' para armazenar informações dos estudantes
            Schema::create('students', function (Blueprint $table) {
                $table->id(); // ID único do estudante
                $table->string('name')->nullable(); // Nome do estudante
                $table->string('email')->unique()->nullable(); // Email único do estudante
                $table->date('date_of_birth')->nullable(); // Data de nascimento
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('students', function (Blueprint $table) {
                if (!Schema::hasColumn('students', 'name')) {
                    $table->string('name')->nullable(); // Nome do estudante
                }
                if (!Schema::hasColumn('students', 'email')) {
                    $table->string('email')->unique()->nullable(); // Email único do estudante
                }
                if (!Schema::hasColumn('students', 'date_of_birth')) {
                    $table->date('date_of_birth')->nullable(); // Data de nascimento
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de estudantes.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
