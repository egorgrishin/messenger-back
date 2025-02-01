<?php
declare(strict_types=1);

namespace App\Services\File\Tests;

use App\Core\Parents\Test;
use App\Services\File\Classes\Saver\Document;
use App\Services\File\Classes\Saver\Image;
use App\Services\File\Classes\Saver\Video;
use App\Services\File\Models\File;
use App\Services\User\Models\User;
use Generator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class CreateFileTest extends Test
{
    private static ?string $prevFileName = null;

    /**
     * @dataProvider data
     */
    public function testCreateFile(UploadedFile $file, string $dir): void
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

        $fileName = $this->getFilename();
        $path = sprintf('%s/%s/%s', $user->id, $dir, $fileName);
        Storage::disk('files')->assertExists($path);

        if ($dir === Video::TYPE) {
            $path = sprintf('%s/%s/%s.%s', $user->id, Video::PREVIEW_TYPE, $fileName, Video::getTargetPreviewExtension());
            Storage::disk('files')->assertExists($path);
        }
    }

    public static function data(): Generator
    {
        yield from [
            [UploadedFile::fake()->image('image.png'), Image::TYPE],
            [UploadedFile::fake()->image('image.jpg'), Image::TYPE],
            [UploadedFile::fake()->image('image.webp'), Image::TYPE],

            [UploadedFile::fake()->create('doc.doc'), Document::TYPE],
            [UploadedFile::fake()->create('doc.docx'), Document::TYPE],
            [UploadedFile::fake()->create('doc.txt'), Document::TYPE],
            [UploadedFile::fake()->create('doc'), Document::TYPE],

            [
                UploadedFile::fake()->createWithContent(
                    'video.mp4',
                    file_get_contents(
                        realpath(__DIR__ . '/../../../../storage/app/tests/file/test_create_video.mp4'),
                    ),
                ),
                Video::TYPE,
            ],
        ];
    }

    private function getFilename(): string
    {
        $file = File::query()
            ->select('filename')
            ->latest()
            ->first();

        assert($file !== null, 'Файл не найден в базе данных');
        assert($file->filename !== self::$prevFileName, 'Имя файла не должно совпадать с именем предыдущего файла');

        return $file->filename;
    }
}