<?php
declare(strict_types=1);

use App\Core\Parents\Test;
use App\Services\Chat\Models\Chat;
use App\Services\Message\Models\Message;
use App\Services\User\Models\User;

final class DeleteMessageTest extends Test
{
    public function testDeleteMessage(): void
    {
        $this->fakeEventWithModel();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Chat $chat */
        $chat = Chat::factory()->create();
        /** @var Message $message */
        $message = Message::factory()->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
        ]);

        $token = $this->jwt->createToken($user);
        $this
            ->delete("/api/v1/messages/$message->id", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertNoContent();

        $this->assertDatabaseCount(Message::class, 0);
    }

    public function testDeleteForeignMessage(): void
    {
        $this->fakeEventWithModel();

        /** @var User $user */
        /** @var User $user2 */
        [$user, $user2] = [User::factory()->create(), User::factory()->create()];
        /** @var Chat $chat */
        $chat = Chat::factory()->create();
        /** @var Message $message */
        $message = Message::factory()->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
        ]);

        $token2 = $this->jwt->createToken($user2);

        $this
            ->delete("/api/v1/messages/$message->id", [], [
                'Authorization' => "Bearer $token2",
            ])
            ->assertForbidden();
    }
}
