<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Saver;

use App\Services\File\Dto\CreateFileDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;
use ImagickException;

class Image implements SaverHandler
{
    private string       $filename;
    private string       $path;
    private string       $fullpath;
    private UploadedFile $file;

    public function __construct(CreateFileDto $dto)
    {
        $this->filename = $this->getFilename($dto->userId);
        $this->path = "$dto->userId/images";
        $this->fullpath = Storage::disk('files')->path($this->path);
        $this->file = $dto->file;
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
        $imagick->setImageFormat('jpg');
        $imagick->setImageCompressionQuality($quality);

        if (!Storage::disk('files')->exists($this->path)) {
            Storage::disk('files')->makeDirectory($this->path);
        }
        $imagick->writeImage("$this->fullpath/$this->filename");

        return $this->filename;
    }

    /**
     * Возвращает название файла с путем к нему
     */
    private function getFilename(int $userId): string
    {
        do {
            $filename = $userId . '_' . Str::ulid()->toBase32() . '.jpg';
            $path = "/$userId/images/$filename";
        } while (Storage::disk('files')->exists($path));
        return $filename;
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
