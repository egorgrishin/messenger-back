<?php
declare(strict_types=1);

namespace Modules\User\Tests;

use Core\Parents\Test;
use Modules\User\Models\User;

final class FindUserTest extends Test
{
    public function testFindUser(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $token = $this->jwt->createToken($user);

        $this
            ->json('GET', "/api/v1/users/$user->id", [], [
                'Authorization' => "Bearer $token",
            ])
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'nick',
                'created_at',
                'updated_at',
            ]);
    }
}
