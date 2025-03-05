<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar a tabela de usuários.
     */
    public function up(): void
    {
        // Verifica se a tabela 'users' já existe
        if (!Schema::hasTable('users')) {
            // Cria a tabela 'users' para armazenar informações dos usuários
            Schema::create('users', function (Blueprint $table) {
                $table->id(); // ID único do usuário
                $table->string('name')->nullable(); // Nome do usuário
                $table->string('email')->unique()->nullable(); // Email único do usuário
                $table->timestamp('email_verified_at')->nullable(); // Timestamp de verificação do email
                $table->string('password')->nullable(); // Senha do usuário
                $table->rememberToken(); // Token de lembrança
                $table->timestamps(); // Timestamps de criação e atualização
            });
        } else {
            // Se a tabela já existe, adiciona colunas se necessário
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'name')) {
                    $table->string('name')->nullable(); // Nome do usuário
                }
                if (!Schema::hasColumn('users', 'email')) {
                    $table->string('email')->unique()->nullable(); // Email único do usuário
                }
                if (!Schema::hasColumn('users', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable(); // Timestamp de verificação do email
                }
                if (!Schema::hasColumn('users', 'password')) {
                    $table->string('password')->nullable(); // Senha do usuário
                }
            });
        }

        // Cria a tabela 'password_reset_tokens' para armazenar tokens de redefinição de senha
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary(); // Email do usuário
                $table->string('token'); // Token de redefinição
                $table->timestamp('created_at')->nullable(); // Timestamp de criação do token
            });
        }

        // Cria a tabela 'sessions' para armazenar sessões de usuários
        if (!Schema::hasTable('sessions')) {
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
     * Reverte as migrações, removendo a tabela de usuários e suas dependências.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
