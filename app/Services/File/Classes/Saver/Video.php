<?php

namespace App\Services\File\Classes\Saver;

use App\Services\File\Dto\CreateFileDto;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Storage;

class Video extends SaverHandler
{
    protected string $previewFileName;
    protected string $previewPath;
    protected string $previewFullPath;
    public const    TYPE         = 'videos';
    public const    PREVIEW_TYPE = 'video_previews';

    public function __construct(CreateFileDto $dto)
    {
        parent::__construct($dto);

        $this->previewFileName = $this->fileName . '.' . self::getTargetPreviewExtension();
        $this->previewPath = $dto->userId . '/' . static::PREVIEW_TYPE;
        $this->previewFullPath = Storage::disk('files')->path($this->previewPath);
        if (!file_exists($this->previewFullPath)) {
            mkdir($this->previewFullPath, recursive: true);
        }
    }

    /**
     * Возвращает расширение, с которым необходимо сохранить файл
     */
    protected function getTargetExtension(): string
    {
        return 'mp4';
    }

    /**
     * Возвращает расширение, с которым необходимо превью для видео
     */
    public static function getTargetPreviewExtension(): string
    {
        return 'jpg';
    }

    /**
     * Сохраняет изображение в хранилище
     */
    public function save(): string
    {
        $this->file->storeAs($this->path, $this->fileName, ['disk' => 'files']);
        $this->savePreview();
        return $this->fileName;
    }

    /**
     * Сохраняет превью видеозаписи
     */
    private function savePreview(): void
    {
        FFMpeg::create()
            ->open($this->fullPath . '/' . $this->fileName)
            ->frame(TimeCode::fromSeconds(0))
            ->save($this->previewFullPath . '/' . $this->previewFileName);
    }
}
