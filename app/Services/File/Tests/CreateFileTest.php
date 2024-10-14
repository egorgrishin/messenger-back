<?php
declare(strict_types=1);

namespace App\Services\File\Tests;

use App\Core\Parents\Test;
use App\Services\User\Models\User;
use Illuminate\Http\UploadedFile;

final class CreateFileTest extends Test
{
    public function testCreateFile(): void
    {
        $user = User::factory()->create();
        $token = $this->jwt->createToken($user);
        $file = UploadedFile::fake()->image('image.png');

        $this
            ->postJson('/api/v1/files', [
                'userId' => $user->id,
                'file'   => $file,
            ], [
                'Authorization' => "Bearer $token",
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'uuid',
                ],
            ]);
    }
}