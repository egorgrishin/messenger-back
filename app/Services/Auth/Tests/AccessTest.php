<?php
declare(strict_types=1);

namespace App\Services\Auth\Tests;

use App\Core\Parents\Test;
use Illuminate\Support\Str;
use App\Services\Auth\Models\RefreshToken;
use App\Services\User\Models\User;

final class AccessTest extends Test
{
    public function testAccessCorrectCredentials(): void
    {
        $user = User::factory()->create([
            'password' => $password = Str::password(),
        ]);

        $this
            ->postJson('/api/v1/access', [
                'email'    => $user->email,
                'password' => $password,
            ])
            ->assertOk()
            ->assertJsonStructure([
                'accessToken',
                'refreshToken',
            ]);

        $this->assertDatabaseCount(RefreshToken::class, 1);
    }

    public function testAccessIncorrectCredentials(): void
    {
        $user = User::factory()->create([
            'password' => $password = Str::password(),
        ]);

        $this
            ->postJson('/api/v1/access', [
                'email'    => $user->email,
                'password' => $password . 'incorrect',
            ])
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Некорректные данные',
            ]);

        $this->assertDatabaseCount(RefreshToken::class, 0);
    }
}
