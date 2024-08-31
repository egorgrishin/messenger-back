<?php
declare(strict_types=1);

namespace App\Services\User\Tests;

use App\Core\Parents\Test;
use App\Services\User\Models\User;

final class GetUsersTest extends Test
{
    public function testGetUsers(): void
    {
        $nick = 'aaa';
        $user = User::factory()->create();
        User::factory()->createMany([
            ['nick' => "{$nick}1"],
            ['nick' => "{$nick}2"],
            ['nick' => "{$nick}3"],
            ['nick' => "{$nick}4"],
        ]);

        $token = $this->jwt->createToken($user);
        $this
            ->json('GET', '/api/v1/users', [
                'nick' => $nick,
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nick',
                    ],
                ],
            ])
            ->assertJsonCount(4, 'data');
    }
}
