<?php
declare(strict_types=1);

namespace App\Modules\Message\Tests;

use App\Core\Parents\Test;
use App\Modules\Chat\Models\Chat;
use App\Modules\Message\Models\Message;
use App\Modules\User\Models\User;

final class GetChatMessagesTest extends Test
{
    public function testGetChatMessages(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $token = $this->jwt->createToken($user);

        /** @var Chat $chat */
        $chat = Chat::factory()->create();

        // Получаем чат от пользователя, не состоящего в нем
        $this
            ->getJson("/api/v1/chats/$chat->id/messages", [
                'Authorization' => "Bearer $token",
            ])
            ->assertForbidden();

        $chat->users()->attach($user->id);
        Message::factory()->count(100)->createQuietly([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
        ]);

        $this
            ->getJson("/api/v1/chats/$chat->id/messages", [
                'Authorization' => "Bearer $token",
            ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'chatId',
                        'userId',
                        'text',
                        'createdAt',
                    ],
                ],
            ])
            ->assertJsonCount(100, 'data');
    }
}
