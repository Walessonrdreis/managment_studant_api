<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppointmentServiceProvider extends ServiceProvider {
    /**
     * Registra quaisquer serviços da aplicação relacionados a agendamentos.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a agendamentos.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
