<?php
declare(strict_types=1);

namespace App\Services\Chat\Tests;

use App\Core\Parents\Test;
use App\Services\Chat\Models\Chat;
use App\Services\User\Models\User;

final class FindChatTest extends Test
{
    public function testFindChat(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Chat $chat */
        $chat = Chat::factory()->create();
        $chat->users()->attach($user->id);

        $token = $this->jwt->createToken($user);

        // Получаем чат
        $this
            ->getJson("/api/v1/chats/$chat->id", [
                'Authorization' => "Bearer $token",
            ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'users' => [
                        '*' => [
                            'id',
                            'nick',
                        ],
                    ],
                ],
            ]);
    }

    public function testFindForeignChat(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();
        /** @var User $user2 */
        $user2 = User::factory()->create();
        /** @var Chat $chat */
        $chat = Chat::factory()->create();
        $chat->users()->attach($user1->id);

        $token2 = $this->jwt->createToken($user2);

        // Получаем чат от пользователя, не состоящего в нем
        $this
            ->getJson("/api/v1/chats/$chat->id", [
                'Authorization' => "Bearer $token2",
            ])
            ->assertForbidden();
    }
}
