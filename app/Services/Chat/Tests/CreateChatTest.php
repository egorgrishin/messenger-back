<?php
declare(strict_types=1);

namespace App\Services\Chat\Tests;

use App\Core\Parents\Test;
use Illuminate\Support\Facades\Event;
use App\Services\Chat\Events\ChatUpdated;
use App\Services\Chat\Models\Chat;
use App\Services\User\Models\User;

final class CreateChatTest extends Test
{
    public function testCreateChat(): void
    {
        Event::fake();

        $user = User::factory()->create();
        User::factory()->create();
        $token = $this->jwt->createToken($user);

        $this
            ->postJson('/api/v1/chats', [
                'recipientId' => 2,
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'isCreated',
                    'chat' => [
                        'id',
                        'users' => [
                            '*' => [
                                'id',
                                'nick',
                                'avatarUrl',
                            ],
                        ],
                    ],
                ],
            ]);
        Event::assertDispatched(ChatUpdated::class, 2);

        $this->assertDatabaseCount(Chat::class, 1)
            ->assertDatabaseCount('chat_user', 2);

        $this
            ->postJson('/api/v1/chats', [
                'recipientId' => 2,
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'isCreated',
                    'chat' => [
                        'id',
                    ],
                ],
            ]);

        $this->assertDatabaseCount(Chat::class, 1)
            ->assertDatabaseCount('chat_user', 2);
    }
}