<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de sessões.
     */
    public function up(): void
    {
        // Verifica se a tabela 'sessions' já existe
        if (!Schema::hasTable('sessions')) {
            // Cria a tabela 'sessions' para armazenar informações de sessões de usuários
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary(); // ID único da sessão
                $table->foreignId('user_id')->nullable()->index(); // ID do usuário associado
                $table->string('ip_address', 45)->nullable(); // Endereço IP do usuário
                $table->text('user_agent')->nullable(); // User agent do navegador
                $table->longText('payload'); // Dados da sessão
                $table->integer('last_activity')->index(); // Timestamp da última atividade
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de sessões.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
