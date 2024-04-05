<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Message\Models\Message;
use Modules\Message\Observers\MessageObserver;

class DatabaseServiceProvider extends ServiceProvider
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
        $this->registerObservers();

        $paths = glob(app_path('Modules/*/Data/Migrations/*'));
        $this->loadMigrationsFrom($paths);
    }

    /**
     * Регистрирует наблюдателей
     */
    private function registerObservers(): void
    {
        Message::observe(MessageObserver::class);
    }
}
