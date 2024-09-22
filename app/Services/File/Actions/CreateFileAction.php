<?php
declare(strict_types=1);

namespace App\Services\File\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\File\Dto\CreateFileDto;
use App\Services\File\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;
use ImagickException;
use Throwable;

final class CreateFileAction extends Action
{
    /**
     * Сохраняет файл в хранилище и информацию о нем в базе данных
     */
    public function run(CreateFileDto $dto): File
    {
        $filename = $this->getFilename($dto->userId);

        try {
            $this->writeFile($filename, $dto);
            return $this->createFile($filename, $dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Сохраняет изображение в хранилище
     * @throws ImagickException
     */
    private function writeFile(string $filename, CreateFileDto $dto): void
    {
        // TODO: add heic|heif support

        $imagick = new Imagick($dto->file->path());

        $quality = $this->getQuality($dto->file);
        $imagick->setImageFormat('jpg');
        $imagick->setImageCompressionQuality($quality);

        $path = "$dto->userId/images";
        $fullpath = Storage::disk('files')->path($path);
        if (!Storage::disk('files')->exists($path)) {
            Storage::disk('files')->makeDirectory($path);
        }
        $imagick->writeImage("$fullpath/$filename");
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

    private function getFilename(int $userId): string
    {
        do {
            $filename = $userId . '_' . Str::ulid()->toBase32() . '.jpg';
            $path = "/$userId/images/$filename";
        } while (Storage::disk('files')->exists($path));
        return $filename;
    }

    /**
     * Сохраняет информацию о файле в базу данных
     * @throws Throwable
     */
    private function createFile(string $filename, CreateFileDto $dto): File
    {
        $file = new File();
        $file->user_id = $dto->userId;
        $file->filename = $filename;
        $file->saveOrFail();

        return $file;
    }
}