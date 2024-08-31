<?php
declare(strict_types=1);

namespace App\Services\User\Tests;

use App\Core\Parents\Test;
use App\Services\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class UpdateUserAvatarTest extends Test
{
    public function testUpdateUserAvatar(): void
    {
        Storage::fake('userAvatars');
        $user = User::factory()->create();
        $token = $this->jwt->createToken($user);

        $this
            ->putJson("/api/v1/users/$user->id/avatar", [
                'avatar' => $file1 = UploadedFile::fake()->image('avatar1.jpg'),
            ], [
                'Authorization' => "Bearer $token"
            ])
            ->assertNoContent();
        Storage::disk('userAvatars')->assertExists($file1->hashName());

        $this
            ->putJson("/api/v1/users/$user->id/avatar", [
                'avatar' => $file2 = UploadedFile::fake()->image('avatar2.jpg'),
            ], [
                'Authorization' => "Bearer $token"
            ])
            ->assertNoContent();
        Storage::disk('userAvatars')->assertExists($file2->hashName());
        Storage::disk('userAvatars')->assertMissing($file1->hashName());
    }
}