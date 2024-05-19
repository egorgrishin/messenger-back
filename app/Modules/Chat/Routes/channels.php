<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;
use App\Modules\User\Models\User;

Broadcast::channel('users.{userId}.chats', function (User $user, int $userId): bool {
    return $user->id === $userId;
});

Broadcast::channel('chats.{chatId}', function (User $user, int $chatId): bool {
    return $user->chats()
        ->where('chats.id', $chatId)
        ->exists();
});
