<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Deleter;

use App\Services\File\Classes\Saver\Video;
use App\Services\File\Models\File;
use Illuminate\Support\Facades\Storage;

final readonly class Deleter
{
    /**
     * Удаляет файл с диска
     */
    public static function run(int $userId, int $type, string $filename): void
    {
        $path = sprintf('%d/%s/%s', $userId, File::typeToString($type), $filename);
        Storage::disk('files')->delete($path);

        if ($type === File::TYPE_VIDEO) {
            self::deleteVideoPreview($userId, $filename);
        }
    }

    /**
     * Удаляет превью видеозаписи с диска
     */
    private static function deleteVideoPreview(int $userId, string $filename): void
    {
        $path = sprintf(
            '%d/%s/%s.%s',
            $userId, Video::PREVIEW_TYPE, $filename, Video::getTargetPreviewExtension()
        );
        Storage::disk('files')->delete($path);
    }
}
