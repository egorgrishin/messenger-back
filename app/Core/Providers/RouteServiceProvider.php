<?php

namespace App\Core\Providers;

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
        $this->loadBroadcastRoutes();
    }

    /**
     * Подключает роуты API различных модулей
     */
    private function loadApiRoutes(): void
    {
        $paths = glob(app_path('Services/*/Routes/rest.*'));
        foreach ($paths as $route_path) {
            $route_data = explode('.', $route_path);
            $version = $route_data[1];
            Route::middleware('api')
                ->prefix('/api/' . $version)
                ->group($route_path);
        }
    }

    /**
     * Подключает роуты трансляции различных модулей
     */
    private function loadBroadcastRoutes(): void
    {
        $paths = glob(app_path('Services/*/Routes/channels.*'));
        foreach ($paths as $route_path) {
            Route::middleware('api')->group($route_path);
        }
    }
}
