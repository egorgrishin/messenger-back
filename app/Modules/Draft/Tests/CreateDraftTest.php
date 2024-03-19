<?php
declare(strict_types=1);

namespace Modules\Draft\Tests;

use Modules\Draft\Models\Draft;
use Core\Parents\Test;
use Illuminate\Support\Str;
use Modules\User\Models\User;

final class CreateDraftTest extends Test
{
    public function testCreateDraft(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();
        $token = $this->jwt->createToken($user1);
        $this
            ->putJson('/api/v1/drafts', [
                'fromId'   => $user1->id,
                'toId' => 2,
                'text'     => Str::random(),
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertUnprocessable();
        $this->assertDatabaseCount(Draft::class, 0);

        /** @var User $user2 */
        $user2 = User::factory()->create();
        $this
            ->putJson('/api/v1/drafts', [
                'fromId'   => $user1->id,
                'toId' => $user2->id,
                'text'     => $text = Str::random(),
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertCreated();
        $this->assertDatabaseHas(Draft::class, [
            'from_id'   => $user1->id,
            'to_id' => $user2->id,
            'text'      => $text,
        ])->assertDatabaseCount(Draft::class, 1);

        $this
            ->putJson('/api/v1/drafts', [
                'fromId'   => $user1->id,
                'toId' => $user2->id,
                'text'     => $text = Str::random(),
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertNoContent();
        $this->assertDatabaseHas(Draft::class, [
            'from_id'   => $user1->id,
            'to_id' => $user2->id,
            'text'      => $text,
        ])->assertDatabaseCount(Draft::class, 1);
    }
}
