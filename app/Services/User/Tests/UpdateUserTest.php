<?php
declare(strict_types=1);

namespace App\Services\User\Tests;

use App\Core\Parents\Test;
use App\Services\User\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

final class UpdateUserTest extends Test
{
    public function testUpdateUser(): void
    {
        $user = User::factory()->create();
        $token = $this->jwt->createToken($user);
        $this
            ->putJson("/api/v1/users/$user->id", [
                'nick'      => $nick = Str::random(24),
                'email'     => $email = Str::random() . '@test.dev',
                'shortLink' => $shortLink = Str::random(),
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nick',
                    'avatarUrl',
                ],
            ])
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $user->id)
                ->where('data.nick', $nick)
                ->where('data.avatarUrl', null)
            );

        $this->assertDatabaseHas(User::class, [
            'nick'       => $nick,
            'email'      => $email,
            'short_link' => $shortLink,
        ]);
    }
}
