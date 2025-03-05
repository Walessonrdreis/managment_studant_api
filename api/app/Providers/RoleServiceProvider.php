<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RoleService;

class RoleServiceProvider extends ServiceProvider {
    public function register(): void
    {
        $this->app->singleton(RoleService::class, function ($app) {
            return new RoleService();
        });
    }

    public function boot(): void
    {
        //
    }
}
