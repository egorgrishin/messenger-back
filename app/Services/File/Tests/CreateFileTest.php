<?php
declare(strict_types=1);

namespace App\Services\File\Tests;

use App\Core\Parents\Test;
use App\Services\User\Models\User;
use Generator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class CreateFileTest extends Test
{
    /**
     * @dataProvider data
     */
    public function testCreateFile(UploadedFile $file): void
    {
        Storage::fake('files');
        $user = User::factory()->create();
        $token = $this->jwt->createToken($user);

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

        Storage::disk('files')->assertExists($user->id);
    }

    public static function data(): Generator
    {
        yield from [
            [UploadedFile::fake()->image('image.png')],
            [UploadedFile::fake()->image('image.jpg')],
            [UploadedFile::fake()->image('image.webp')],

            [UploadedFile::fake()->create('doc.doc')],
            [UploadedFile::fake()->create('doc.docx')],
            [UploadedFile::fake()->create('doc.txt')],
            [UploadedFile::fake()->create('doc')],

            [UploadedFile::fake()->create('video.mp4')],
        ];
    }
}