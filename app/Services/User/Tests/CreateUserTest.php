<?php
declare(strict_types=1);

namespace App\Services\User\Tests;

use App\Core\Parents\Test;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\User\Models\User;

final class CreateUserTest extends Test
{
    public function testCreateUser(): void
    {
        Storage::fake('userAvatars');

        $this
            ->postJson('/api/v1/users', [
                'nick'                  => $nick = Str::random(),
                'password'              => $pass = Str::random(),
                'password_confirmation' => $pass,
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nick',
                    'createdAt',
                    'updatedAt',
                ],
            ]);

        $this->assertDatabaseHas(User::class, [
            'nick' => $nick,
        ])->assertDatabaseCount(User::class, 1);

        $this
            ->postJson('/api/v1/users', [
                'nick'                  => $nick = Str::random(),
                'password'              => $pass = Str::random(),
                'password_confirmation' => $pass,
                'avatar'                => $file = UploadedFile::fake()->image('name.jpg')
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nick',
                    'createdAt',
                    'updatedAt',
                ],
            ]);

        $this->assertDatabaseHas(User::class, [
            'nick' => $nick,
        ])->assertDatabaseCount(User::class, 2);

        Storage::disk('userAvatars')->assertExists($file->hashName());
    }
}
