<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações para criar as tabelas de jobs.
     */
    public function up(): void
    {
        // Cria a tabela 'jobs' para armazenar informações sobre jobs em fila
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // ID único do job
            $table->string('queue')->index(); // Nome da fila do job
            $table->longText('payload'); // Dados do job
            $table->unsignedTinyInteger('attempts'); // Número de tentativas
            $table->unsignedInteger('reserved_at')->nullable(); // Timestamp de reserva
            $table->unsignedInteger('available_at'); // Timestamp de disponibilidade
            $table->unsignedInteger('created_at'); // Timestamp de criação
        });

        // Cria a tabela 'job_batches' para gerenciar lotes de jobs
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // ID único do lote
            $table->string('name'); // Nome do lote
            $table->integer('total_jobs'); // Total de jobs no lote
            $table->integer('pending_jobs'); // Jobs pendentes
            $table->integer('failed_jobs'); // Jobs que falharam
            $table->longText('failed_job_ids'); // IDs dos jobs que falharam
            $table->mediumText('options')->nullable(); // Opções do lote
            $table->integer('cancelled_at')->nullable(); // Timestamp de cancelamento
            $table->integer('created_at'); // Timestamp de criação
            $table->integer('finished_at')->nullable(); // Timestamp de finalização
        });

        // Cria a tabela 'failed_jobs' para armazenar jobs que falharam
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // ID único do job falhado
            $table->string('uuid')->unique(); // UUID único do job
            $table->text('connection'); // Conexão utilizada
            $table->text('queue'); // Fila do job
            $table->longText('payload'); // Dados do job
            $table->longText('exception'); // Exceção gerada
            $table->timestamp('failed_at')->useCurrent(); // Timestamp de falha
        });
    }

    /**
     * Reverte as migrações, removendo as tabelas de jobs.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
