<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Student_SubjectService;

class Student_SubjectServiceProvider extends ServiceProvider {
    /**
     * Registra quaisquer serviços da aplicação relacionados a disciplinas dos estudantes.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        $this->app->singleton(Student_SubjectService::class, function ($app) {
            return new Student_SubjectService();
        });
    }

    /**
     * Inicializa quaisquer serviços da aplicação relacionados a disciplinas dos estudantes.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
