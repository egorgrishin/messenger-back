<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;
use App\Services\User\Models\User;

Broadcast::channel('users.{userId}.online', function (User $user, int $userId): bool {
    return $user->id === $userId;
});
