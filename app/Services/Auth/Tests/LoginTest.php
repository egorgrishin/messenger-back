<?php
declare(strict_types=1);

namespace App\Services\Auth\Tests;

use App\Core\Parents\Test;
use Illuminate\Support\Str;
use App\Services\Auth\Models\RefreshToken;
use App\Services\User\Models\User;

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
