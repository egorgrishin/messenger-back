<?php
declare(strict_types=1);

namespace Modules\User\Tests;

use Core\Parents\Test;
use Modules\User\Models\Friendship;
use Modules\User\Models\User;

final class AddUserFriendTest extends Test
{
    public function testUserSubscription(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();
        $token = $this->jwt->createToken($user1);

        $this
            ->put("/api/v1/users/$user1->id/friends/2", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertUnprocessable();

        /** @var User $user2 */
        $user2 = User::factory()->create();
        $this
            ->put("/api/v1/users/$user1->id/friends/$user2->id", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertNoContent();

        $this
            ->put("/api/v1/users/$user1->id/friends/$user2->id", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertBadRequest();

        $this->assertDatabaseHas(Friendship::class, [
            'user_id'     => $user1->id,
            'friend_id'   => $user2->id,
            'is_accepted' => 0,
        ])->assertDatabaseCount(Friendship::class, 1);
    }

    public function testAddUserFriend(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();
        /** @var User $user2 */
        $user2 = User::factory()->create();

        $user2->friends()->attach($user1);
        $token = $this->jwt->createToken($user1);

        $this->assertDatabaseHas(Friendship::class, [
            'user_id'     => $user2->id,
            'friend_id'   => $user1->id,
            'is_accepted' => 0,
        ])->assertDatabaseCount(Friendship::class, 1);

        $this
            ->put("/api/v1/users/$user1->id/friends/$user2->id", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertNoContent();
        $this->assertDatabaseHas(Friendship::class, [
            'user_id'     => $user1->id,
            'friend_id'   => $user2->id,
            'is_accepted' => 1,
        ])->assertDatabaseHas(Friendship::class, [
            'user_id'     => $user2->id,
            'friend_id'   => $user1->id,
            'is_accepted' => 1,
        ])->assertDatabaseCount(Friendship::class, 2);
    }
}
