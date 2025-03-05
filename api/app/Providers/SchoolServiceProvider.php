<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SchoolService;

class SchoolServiceProvider extends ServiceProvider {
    /**
     * Registra quaisquer serviços da aplicação relacionados a escolas.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(SchoolService::class, function ($app) {
            return new SchoolService();
        });
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a escolas.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
