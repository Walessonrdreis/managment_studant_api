<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ClassroomService;

class ClassroomServiceProvider extends ServiceProvider
{
    /**
     * Registra quaisquer serviços da aplicação relacionados a salas de aula.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(ClassroomService::class, function ($app) {
            return new ClassroomService();
        });
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a salas de aula.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
