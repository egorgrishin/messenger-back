<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Deleter;

use App\Services\File\Classes\Saver\Video;
use App\Services\File\Models\File;
use Illuminate\Support\Facades\Storage;

final readonly class Deleter
{
    private int    $userId;
    private int    $type;
    private string $dir;
    private string $filename;

    public function __construct(File $fileModel)
    {
        $this->userId = $fileModel->user_id;
        $this->type = $fileModel->type;
        $this->dir = File::typeToString($fileModel->type);
        $this->filename = $fileModel->filename;
    }

    /**
     * Удаляет файл с диска
     */
    public function run(): void
    {
        $path = sprintf('%d/%s/%s', $this->userId, $this->dir, $this->filename);
        Storage::disk('files')->delete($path);

        if ($this->type === File::TYPE_VIDEO) {
            $this->deleteVideoPreview();
        }
    }

    /**
     * Удаляет превью видеозаписи с диска
     */
    private function deleteVideoPreview(): void
    {
        $path = sprintf(
            '%d/%s/%s.%s',
            $this->userId, Video::PREVIEW_TYPE, $this->filename, Video::getTargetPreviewExtension()
        );
        Storage::disk('files')->delete($path);
    }
}