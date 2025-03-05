<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar as tabelas de cache.
     */
    public function up(): void
    {
        // Cria a tabela 'cache' para armazenar chaves e valores em cache
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Chave única para o cache
            $table->mediumText('value'); // Valor armazenado no cache
            $table->integer('expiration'); // Tempo de expiração do cache
        });

        // Cria a tabela 'cache_locks' para gerenciar bloqueios de cache
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Chave única para o bloqueio
            $table->string('owner'); // Proprietário do bloqueio
            $table->integer('expiration'); // Tempo de expiração do bloqueio
        });
    }

    /**
     * Reverte as migrações, removendo as tabelas de cache.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
