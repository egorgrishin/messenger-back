<?php
declare(strict_types=1);

use App\Services\User\Resources\UserResource;
use Illuminate\Support\Facades\Broadcast;
use App\Services\User\Models\User;

Broadcast::channel('online-users', function (?User $user) {
    return $user ? (new UserResource($user))->resolve() : null;
});
