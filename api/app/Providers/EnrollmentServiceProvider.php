<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EnrollmentService;

class EnrollmentServiceProvider extends ServiceProvider
{
    /**
     * Registra quaisquer serviços da aplicação relacionados a matrículas.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(EnrollmentService::class, function ($app) {
            return new EnrollmentService();
        });
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a matrículas.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
