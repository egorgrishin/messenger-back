<?php
declare(strict_types=1);

namespace Modules\Auth\Tests;

use Core\Parents\Test;
use Illuminate\Support\Str;
use Modules\Auth\Models\RefreshToken;
use Modules\User\Models\User;

final class LoginTest extends Test
{
    public function testLogin(): void
    {
        User::factory()->create([
            'nick'     => $nick = Str::random(),
            'password' => $password = Str::password(),
        ]);

        $this
            ->postJson('/api/v1/login', [
                'nick'     => $nick,
                'password' => $password . 'incorrect',
            ])
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Некорректные данные',
            ]);

        $this
            ->postJson('/api/v1/login', [
                'nick'     => $nick,
                'password' => $password,
            ])
            ->assertOk()
            ->assertJsonStructure([
                'accessToken',
                'refreshToken',
            ]);

        $this->assertDatabaseCount(RefreshToken::class, 1);
    }
}
