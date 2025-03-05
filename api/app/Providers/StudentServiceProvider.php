<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StudentService;

class StudentServiceProvider extends ServiceProvider
{
    /**
     * Registra quaisquer serviços da aplicação relacionados a estudantes.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(StudentService::class, function ($app) {
            return new StudentService();
        });
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a estudantes.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
