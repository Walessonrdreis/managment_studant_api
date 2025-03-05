<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AttendanceService;

class AttendanceServiceProvider extends ServiceProvider {
    /**
     * Registra quaisquer serviços da aplicação relacionados a presenças.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(AttendanceService::class, function ($app) {
            return new AttendanceService();
        });
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a presenças.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
