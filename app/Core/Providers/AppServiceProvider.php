<?php

namespace App\Core\Providers;

use App\Services\Chat\Listeners\UpdateChatLastMessage;
use App\Services\Message\Events\MessageCreated;
use App\Services\Message\Events\MessageDeleted;
use App\Services\Message\Events\MessageUpdated;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Broadcast::routes(['middleware' => ['api']]);

        Event::listen(
            MessageCreated::class,
            UpdateChatLastMessage::class,
        );
        Event::listen(
            MessageUpdated::class,
            UpdateChatLastMessage::class,
        );
        Event::listen(
            MessageDeleted::class,
            UpdateChatLastMessage::class,
        );
    }
}
