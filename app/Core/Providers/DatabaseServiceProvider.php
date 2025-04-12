<?php

namespace App\Core\Providers;

use App\Services\File\Models\File;
use App\Services\File\Observers\FileObserver;
use App\Services\Message\Observers\MessageCommitObserver;
use Illuminate\Support\ServiceProvider;
use App\Services\Message\Models\Message;
use App\Services\Message\Observers\MessageObserver;

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

        $paths = glob(app_path('Services/*/Data/Migrations/*'));
        $this->loadMigrationsFrom($paths);
    }

    /**
     * Регистрирует наблюдателей
     */
    private function registerObservers(): void
    {
        File::observe(FileObserver::class);
        Message::observe([
            MessageObserver::class,
            MessageCommitObserver::class,
        ]);
    }
}
