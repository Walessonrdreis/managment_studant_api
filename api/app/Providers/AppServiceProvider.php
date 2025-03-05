<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra quaisquer serviços da aplicação.
     * Este método é usado para registrar serviços que a aplicação irá utilizar.
     */
    public function register(): void
    {
        // Aqui você pode registrar outros serviços globais, se necessário
    }

    /**
     * Inicializa quaisquer serviços da aplicação.
     * Este método é usado para configurar serviços após serem registrados.
     */
    public function boot(): void
    {
        //
    }
}
