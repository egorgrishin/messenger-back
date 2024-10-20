<?php

namespace App\Core\Providers;

use App\Core\Classes\WebSocket\Handler;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['api']]);
        Route::post('/broadcasting/webhook', Handler::class)->middleware('api');
    }
}
