<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Message\Models\Message;
use Modules\Message\Observers\MessageObserver;
use Modules\User\Models\Friendship;
use Modules\User\Observers\FriendshipObserver;

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
        Friendship::observe(FriendshipObserver::class);
        Message::observe(MessageObserver::class);
    }
}
