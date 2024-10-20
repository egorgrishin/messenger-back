<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Saver;

use App\Services\File\Dto\CreateFileDto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class SaverHandler
{
    abstract public function __construct(CreateFileDto $dto);

    /**
     * Сохраняет файл в хранилище
     */
    abstract public function save(): string;

    /**
     * Возвращает название файла с путем к нему
     */
    protected function getFilename(int $userId): string
    {
        do {
            $filename = $userId . '_' . Str::ulid()->toBase32() . '.jpg';
            $path = "/$userId/images/$filename";
        } while (Storage::disk('files')->exists($path));
        return $filename;
    }
}