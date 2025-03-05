<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AppointmentService;

class AppointmentServiceProvider extends ServiceProvider {
    /**
     * Registra quaisquer serviços da aplicação relacionados a agendamentos.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(AppointmentService::class, function ($app) {
            return new AppointmentService();
        });
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
