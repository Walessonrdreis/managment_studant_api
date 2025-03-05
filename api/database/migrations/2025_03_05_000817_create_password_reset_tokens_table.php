<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de tokens de redefinição de senha.
     */
    public function up(): void
    {
        // Verifica se a tabela 'password_reset_tokens' já existe
        if (!Schema::hasTable('password_reset_tokens')) {
            // Cria a tabela 'password_reset_tokens' para armazenar tokens de redefinição de senha
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary(); // Email do usuário
                $table->string('token'); // Token de redefinição
                $table->timestamp('created_at')->nullable(); // Timestamp de criação do token
            });
        }
    }

    /**
     * Reverte as migrações, removendo a tabela de tokens de redefinição de senha.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
