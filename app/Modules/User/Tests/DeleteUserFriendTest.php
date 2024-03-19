<?php
declare(strict_types=1);

namespace Modules\User\Tests;

use Core\Parents\Test;
use Modules\User\Models\Friendship;
use Modules\User\Models\User;

final class DeleteUserFriendTest extends Test
{
    public function testDeleteUserFriend(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();
        $token = $this->jwt->createToken($user1);

        $this
            ->delete("/api/v1/users/$user1->id/friends/2", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertUnprocessable();

        /** @var User $user2 */
        $user2 = User::factory()->create();
        $this
            ->delete("/api/v1/users/$user1->id/friends/$user2->id", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertBadRequest();

        $user1->friends()->attach($user2);
        $this
            ->delete("/api/v1/users/$user1->id/friends/$user2->id", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertNoContent();

        $this->assertDatabaseMissing(Friendship::class, [
            'user_id'   => $user1->id,
            'friend_id' => $user2->id,
        ])->assertDatabaseCount(Friendship::class, 0);
    }
}
