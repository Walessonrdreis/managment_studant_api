<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de escolas.
     */
    public function up(): void
    {
        // Verifica se a tabela 'schools' já existe
        if (!Schema::hasTable('schools')) {
            // Cria a tabela 'schools' para armazenar informações das escolas
            Schema::create('schools', function (Blueprint $table) {
                $table->id(); // ID único da escola
                $table->string('name')->nullable(); // Nome da escola
                $table->string('address')->nullable(); // Endereço da escola
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('schools', function (Blueprint $table) {
                if (!Schema::hasColumn('schools', 'name')) {
                    $table->string('name')->nullable(); // Nome da escola
                }
                if (!Schema::hasColumn('schools', 'address')) {
                    $table->string('address')->nullable(); // Endereço da escola
                }
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de escolas.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
