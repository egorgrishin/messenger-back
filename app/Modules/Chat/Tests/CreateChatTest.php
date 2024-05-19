<?php
declare(strict_types=1);

namespace Modules\Chat\Tests;

use Core\Parents\Test;
use Illuminate\Support\Facades\Event;
use Modules\Chat\Events\ChatUpdated;
use Modules\Chat\Models\Chat;
use Modules\User\Models\User;

final class CreateChatTest extends Test
{
    public function testCreateChat(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        User::factory()->create();
        $token = $this->jwt->createToken($user);

        $this
            ->postJson('/api/v1/chats', [
                'isDialog' => true,
                'users'    => [1, 2],
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'isDialog',
                ],
            ]);
        Event::assertDispatched(ChatUpdated::class, 2);

        $this->assertDatabaseCount(Chat::class, 1)
            ->assertDatabaseCount('chat_user', 2);

        $this
            ->postJson('/api/v1/chats', [
                'isDialog' => true,
                'users'    => [1, 2],
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'Диалог уже существует',
            ]);
    }
}