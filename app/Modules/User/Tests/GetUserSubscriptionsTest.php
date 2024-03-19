<?php
declare(strict_types=1);

namespace Modules\User\Tests;

use Core\Parents\Test;
use Modules\User\Models\User;

final class GetUserSubscriptionsTest extends Test
{
    public function testGetUserSubscriptions(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();
        /** @var User $user2 */
        $user2 = User::factory()->create();

        $user1->friends()->attach($user2);
        $token = $this->jwt->createToken($user1);

        $this
            ->json('GET', "/api/v1/users/$user1->id/subscriptions", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'nick',
                ],
            ])
            ->assertJsonCount(1);
    }
}
