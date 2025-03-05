<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TeacherService;

class TeacherServiceProvider extends ServiceProvider {
    /**
     * Registra quaisquer serviços da aplicação relacionados a professores.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(TeacherService::class, function ($app) {
            return new TeacherService();
        });
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a professores.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
