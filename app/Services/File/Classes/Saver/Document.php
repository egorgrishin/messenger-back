<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Saver;

use App\Services\File\Dto\CreateFileDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Document extends SaverHandler
{
    private string       $filename;
    private string       $path;
    private string       $fullpath;
    private UploadedFile $file;

    public function __construct(CreateFileDto $dto)
    {
        $this->filename = $this->getFilename($dto->userId);
        $this->path = "$dto->userId/documents";
        $this->fullpath = Storage::disk('files')->path($this->path);
        $this->file = $dto->file;
    }

    /**
     * Сохраняет изображение в хранилище
     */
    public function save(): string
    {
        if (!Storage::disk('files')->exists($this->path)) {
            Storage::disk('files')->makeDirectory($this->path);
        }

        $this->file->storeAs($this->fullpath);
        return $this->filename;
    }
}