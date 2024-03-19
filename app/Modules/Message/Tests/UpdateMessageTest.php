<?php
declare(strict_types=1);

namespace Modules\Message\Tests;

use Core\Parents\Test;
use Illuminate\Support\Str;
use Modules\Message\Models\Message;
use Modules\User\Models\User;

final class UpdateMessageTest extends Test
{
    private function testUpdateMessageNotFound(): void
    {
        /** @var User $user1 */
        /** @var User $user2 */
        [$user1, $user2] = [User::factory()->create(), User::factory()->create()];
        $token1 = $this->jwt->createToken($user1);

        /** @var Message $message */
        $message = Message::factory()->create([
            'from_id' => $user1->id,
            'to_id'   => $user2->id,
        ]);
        $incorrectId = $message->id + 1;

        $this->patchJson("/api/v1/messages/$incorrectId", [
            'text' => Str::random(),
        ], [
            'Authorization' => "Bearer $token1"
        ])->assertNotFound();
    }

    private function testUpdateMessageForbidden(): void
    {
        /** @var User $user1 */
        /** @var User $user2 */
        [$user1, $user2] = [User::factory()->create(), User::factory()->create()];
        $token2 = $this->jwt->createToken($user2);

        /** @var Message $message */
        $message = Message::factory()->create([
            'from_id' => $user1->id,
            'to_id'   => $user2->id,
        ]);

        $this->patchJson("/api/v1/messages/$message->id", [
            'text' => Str::random(),
        ], [
            'Authorization' => "Bearer $token2"
        ])->assertForbidden();
    }

    private function testUpdateMessageUnprocessable(): void
    {
        /** @var User $user1 */
        /** @var User $user2 */
        [$user1, $user2] = [User::factory()->create(), User::factory()->create()];
        $token1 = $this->jwt->createToken($user1);

        /** @var Message $message */
        $message = Message::factory()->create([
            'from_id' => $user1->id,
            'to_id'   => $user2->id,
            'text'    => $oldText = Str::random(),
        ]);

        $this->patchJson("/api/v1/messages/$message->id", [
            'text' => $oldText,
        ], [
            'Authorization' => "Bearer $token1"
        ])->assertUnprocessable();
    }

    private function testUpdateMessageNoContent(): void
    {
        /** @var User $user1 */
        /** @var User $user2 */
        [$user1, $user2] = [User::factory()->create(), User::factory()->create()];
        $token1 = $this->jwt->createToken($user1);

        /** @var Message $message */
        $message = Message::factory()->create([
            'from_id' => $user1->id,
            'to_id'   => $user2->id,
        ]);

        $this->patchJson("/api/v1/messages/$message->id", [
            'text' => $newText = Str::random(),
        ], [
            'Authorization' => "Bearer $token1"
        ])->assertNoContent();

        $this->assertDatabaseHas(Message::class, [
            'text' => $newText,
        ])->assertDatabaseCount(Message::class, 1);
    }
}
