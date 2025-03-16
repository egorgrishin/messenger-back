<?php
declare(strict_types=1);

namespace App\Services\Chat\Tests;

use App\Core\Parents\Test;
use Illuminate\Support\Collection;
use App\Services\Chat\Models\Chat;
use App\Services\User\Models\User;

final class GetUserChatsTest extends Test
{
    public function testGetUserChats(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $token = $this->jwt->createToken($user);

        $this
            ->getJson("/api/v1/users/$user->id/chats", [
                'Authorization' => "Bearer $token",
            ])
            ->assertJsonCount(0, 'data');

        /** @var Collection $chats */
        $chats = Chat::factory()->count($count = 10)->create();
        $user->chats()->attach($chats);

        $this
            ->getJson("/api/v1/users/$user->id/chats", [
                'Authorization' => "Bearer $token",
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'lastMessageId',
                        'lastMessage',
                    ],
                ],
            ])
            ->assertJsonCount($count, 'data');
    }
}
