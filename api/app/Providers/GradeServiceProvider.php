<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GradeService;

class GradeServiceProvider extends ServiceProvider
{
    /**
     * Registra quaisquer serviços da aplicação relacionados a notas.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(GradeService::class, function ($app) {
            return new GradeService();
        });
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a notas.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
