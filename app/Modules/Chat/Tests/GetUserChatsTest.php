<?php
declare(strict_types=1);

namespace Modules\Chat\Tests;

use Core\Parents\Test;
use Illuminate\Support\Collection;
use Modules\Chat\Models\Chat;
use Modules\User\Models\User;

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
                        'title',
                        'isDialog',
                        'lastMessageId',
                        'lastMessage',
                    ],
                ],
            ])
            ->assertJsonCount($count, 'data');
    }
}
