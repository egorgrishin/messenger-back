<?php
declare(strict_types=1);

namespace App\Services\Message\Tests;

use App\Core\Parents\Model;
use App\Core\Parents\Test;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use App\Services\Chat\Events\ChatUpdated;
use App\Services\Chat\Models\Chat;
use App\Services\Message\Events\NewMessage;
use App\Services\Message\Models\Message;
use App\Services\User\Models\User;

final class CreateMessageTest extends Test
{
    public function testCreateMessage(): void
    {
        $this->fakeEventWithModel();

        /** @var User $user */
        /** @var User $user2 */
        [$user, $user2] = [User::factory()->create(), User::factory()->create()];
        $token = $this->jwt->createToken($user);
        /** @var Chat $chat */
        $chat = Chat::factory()->create();

        // Создаем сообщение в чате от пользователя, не состоящего в нем
        $this
            ->postJson("/api/v1/messages", [
                'chatId' => $chat->id,
                'text'   => $text = Str::random(),
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertForbidden();

        $this->assertDatabaseCount(Message::class, 0);
        $chat->users()->attach([$user->id, $user2->id]);

        // Создаем сообщение в чате от пользователя, не состоящего в нем
        $this
            ->postJson("/api/v1/messages", [
                'chatId' => $chat->id,
                'text'   => $text = Str::random(),
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertCreated();

        Event::assertDispatched(NewMessage::class);
        Event::assertDispatchedTimes(ChatUpdated::class, 2);
        $this
            ->assertDatabaseHas(Message::class, [
                'chat_id' => $chat->id,
                'text'    => $text,
                'user_id' => $user->id,
            ])
            ->assertDatabaseCount(Message::class, 1)
            ->assertDatabaseHas(Chat::class, [
                'id'              => $chat->id,
                'last_message_id' => 1,
            ]);
    }
}
