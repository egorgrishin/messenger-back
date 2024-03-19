<?php
declare(strict_types=1);

namespace Modules\Auth\Tests;

use Core\Parents\Test;
use Illuminate\Support\Str;
use Modules\Auth\Models\RefreshToken;
use Modules\User\Models\User;

final class RefreshTest extends Test
{
    public function testRefresh(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'nick'     => $nick = Str::random(),
            'password' => $password = Str::password(),
        ]);
        $refreshToken = $this->getRefreshToken($nick, $password);

        $this->assertDatabaseHas(RefreshToken::class, [
            'ulid'       => $refreshToken,
            'user_id'    => $user->id,
            'is_blocked' => 0,
        ])->assertDatabaseCount(RefreshToken::class, 1);

        $response = $this->postJson('/api/v1/refresh', [
            'refreshToken' => $refreshToken,
        ]);
        $response
            ->assertOk()
            ->assertJsonStructure([
                'accessToken',
                'refreshToken',
            ]);
        $newRefreshToken = $response->json('refreshToken');

        $this
            ->assertDatabaseHas(RefreshToken::class, [
                'ulid'       => $refreshToken,
                'user_id'    => $user->id,
                'is_blocked' => 1,
            ])
            ->assertDatabaseHas(RefreshToken::class, [
                'ulid'       => $newRefreshToken,
                'user_id'    => $user->id,
                'is_blocked' => 0,
            ])
            ->assertDatabaseCount(RefreshToken::class, 2);

        $response = $this->postJson('/api/v1/refresh', [
            'refreshToken' => $refreshToken,
        ]);
        $response->assertUnauthorized();

        $this->assertDatabaseMissing(RefreshToken::class, [
            'user_id'    => $user->id,
            'is_blocked' => 0,
        ])->assertDatabaseCount(RefreshToken::class, 2);
    }

    private function getRefreshToken(string $nick, string $password): string
    {
        return $this->postJson('/api/v1/login', [
            'nick'     => $nick,
            'password' => $password,
        ])->json('refreshToken');
    }
}
