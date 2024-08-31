<?php
declare(strict_types=1);

namespace App\Services\Auth\Tests;

use App\Core\Parents\Test;
use Illuminate\Support\Str;
use App\Services\Auth\Models\RefreshToken;
use App\Services\User\Models\User;

final class RefreshTest extends Test
{
    public function testRefresh(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'nick'     => $nick = Str::random(),
            'password' => $password = Str::password(),
        ]);
        // Получаем токен
        $refreshToken = $this->getRefreshToken($nick, $password);

        $this->assertDatabaseHas(RefreshToken::class, [
            'ulid'       => $refreshToken,
            'user_id'    => $user->id,
            'is_blocked' => 0,
        ])->assertDatabaseCount(RefreshToken::class, 1);

        // Успешно обновляем токен
        $response = $this->postJson('/api/v1/refresh', [
            'refreshToken' => $refreshToken,
        ]);
        $response->assertOk()->assertJsonStructure([
            'accessToken',
            'refreshToken',
        ]);
        $newRefreshToken = $response->json('refreshToken');

        $this->assertDatabaseHas(RefreshToken::class, [
            'ulid'       => $refreshToken,
            'user_id'    => $user->id,
            'is_blocked' => 1,
        ])->assertDatabaseHas(RefreshToken::class, [
            'ulid'       => $newRefreshToken,
            'user_id'    => $user->id,
            'is_blocked' => 0,
        ])->assertDatabaseCount(RefreshToken::class, 2);

        // Отправляем на обновление старый токен. Блокируется все семейство и возвращается статус 401
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
