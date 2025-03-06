<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeToAppointmentsTable extends Migration
{
    /**
     * Executa as migrações para adicionar a coluna time à tabela appointments.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('time')->nullable()->after('date'); // Hora do agendamento
        });
    }

    /**
     * Reverte as migrações, removendo a coluna time da tabela appointments.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('time');
        });
    }
}
