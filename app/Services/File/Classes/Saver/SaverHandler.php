<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Saver;

use App\Services\File\Dto\CreateFileDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class SaverHandler
{
    protected string       $path;
    protected string       $fullPath;
    protected string       $fileName;
    protected UploadedFile $file;
    public const TYPE          = 'undefined';
    public const DATABASE_TYPE = 'document';

    public function __construct(CreateFileDto $dto)
    {
        $this->file = $dto->file;
        $this->fileName = $this->getFilename($dto->userId);

        $this->path = $dto->userId . '/' . static::TYPE;
        $this->fullPath = Storage::disk('files')->path($this->path);
        if (!file_exists($this->fullPath)) {
            mkdir($this->fullPath, recursive: true);
        }
    }

    /**
     * Возвращает расширение, с которым необходимо сохранить файл
     */
    abstract protected function getTargetExtension(): string;

    /**
     * Сохраняет файл в хранилище
     */
    abstract public function save(): string;

    /**
     * Возвращает название файла с путем к нему
     */
    protected function getFilename(int $userId): string
    {
        $extension = $this->getTargetExtension();
        $extension = $extension ? '.' . $extension : '';
        do {
            $filename = sprintf('%d_%s%s', $userId, Str::ulid()->toBase32(), $extension);
            $path = sprintf('%d/%s/%s', $userId, static::TYPE, $filename);
        } while (Storage::disk('files')->exists($path));
        return $filename;
    }

    /**
     * Возвращает тип файла для записи в базу данных
     */
    public function getDatabaseType(): int
    {
        return Config::get('files.types.' . static::DATABASE_TYPE, Document::DATABASE_TYPE);
    }
}
