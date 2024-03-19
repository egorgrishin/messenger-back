<?php
declare(strict_types=1);

namespace Modules\Message\Tests;

use Core\Parents\Test;
use Illuminate\Support\Str;
use Modules\Draft\Models\Draft;
use Modules\Message\Models\Message;
use Modules\User\Models\User;

final class CreateMessageTest extends Test
{
    public function testCreateMessageWithoutDraft(): void
    {
        /** @var User $user1 */
        /** @var User $user2 */
        [$user1, $user2] = [User::factory()->create(), User::factory()->create()];

        $this->assertMessageCreated($user1, $user2);
    }

    public function testCreateMessageWithDraft(): void
    {
        /** @var User $user1 */
        /** @var User $user2 */
        [$user1, $user2] = [User::factory()->create(), User::factory()->create()];

        Draft::factory()->create($draft_data = [
            'from_id' => $user1->id,
            'to_id'   => $user2->id,
        ]);
        $this
            ->assertDatabaseMissing(Draft::class, array_merge(
                $draft_data, ['text' => null]
            ))
            ->assertDatabaseHas(Draft::class, $draft_data)
            ->assertDatabaseCount(Draft::class, 1);

        $this->assertMessageCreated($user1, $user2);

        $this->assertDatabaseHas(Draft::class, array_merge(
            $draft_data, ['text' => null]
        ))->assertDatabaseCount(Draft::class, 1);
    }

    private function assertMessageCreated(User $user1, User $user2): void
    {
        $token = $this->jwt->createToken($user1);
        $this
            ->postJson('/api/v1/messages', [
                'fromId' => $user1->id,
                'toId'   => $user2->id,
                'text'   => $text = Str::random(),
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertCreated();
        $this->assertDatabaseHas(Message::class, [
            'from_id' => $user1->id,
            'to_id'   => $user2->id,
            'text'    => $text,
        ])->assertDatabaseCount(Message::class, 1);
    }
}
