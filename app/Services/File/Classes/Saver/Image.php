<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Saver;

use Illuminate\Http\UploadedFile;
use Imagick;
use ImagickException;

class Image extends SaverHandler
{
    public const TYPE = 'images';

    /**
     * Возвращает расширение, с которым необходимо сохранить файл
     */
    protected function getTargetExtension(): string
    {
        return 'jpg';
    }

    /**
     * Сохраняет изображение в хранилище
     * @throws ImagickException
     */
    public function save(): string
    {
        // TODO: add heic|heif support
        $imagick = new Imagick($this->file->path());

        $quality = $this->getQuality($this->file);
        $imagick->setImageFormat($this->getTargetExtension());
        $imagick->setImageCompressionQuality($quality);

        $imagick->writeImage("$this->fullPath/$this->fileName");
        return $this->fileName;
    }

    /**
     * Возвращает качество, до которого необходимо сжать изображение
     */
    private function getQuality(UploadedFile $file): int
    {
        return match ($file->getMimeType()) {
            'image/png',
            'image/jpeg',
            'image/webp' => 40,
            'image/bmp',
            'image/gif'  => 60,
            default      => 75,
        };
    }
}
