<?php
declare(strict_types=1);

namespace App\Modules\Chat\Tests;

use App\Core\Parents\Test;
use Illuminate\Support\Facades\Event;
use App\Modules\Chat\Events\ChatUpdated;
use App\Modules\Chat\Models\Chat;
use App\Modules\User\Models\User;

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