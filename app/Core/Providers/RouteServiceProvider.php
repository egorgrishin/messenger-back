<?php

namespace Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadApiRoutes();
    }

    /**
     * Подключает роуты API различных модулей
     */
    private function loadApiRoutes(): void
    {
        $paths = glob(app_path('Modules/*/Routes/rest.*'));
        foreach ($paths as $route_path) {
            $route_data = explode('.', $route_path);
            $version = $route_data[1];
            Route::middleware('api')
                ->prefix('/api/' . $version)
                ->group($route_path);
        }
    }
}
