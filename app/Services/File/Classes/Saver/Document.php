<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Saver;

use App\Services\File\Dto\CreateFileDto;
use Illuminate\Http\UploadedFile;

class Document extends SaverHandler
{
    protected string $targetExtension = '';
    public const TYPE = 'docs';

    public function __construct(CreateFileDto $dto)
    {
        $this->setTargetExtension($dto->file);
        parent::__construct($dto);
    }

    /**
     * Возвращает расширение, с которым необходимо сохранить файл
     */
    protected function getTargetExtension(): string
    {
        return $this->targetExtension;
    }

    /**
     * Устанавливает расширение, с которым необходимо сохранить файл
     */
    private function setTargetExtension(UploadedFile $file): void
    {
        $this->targetExtension = $file->getClientOriginalExtension();
    }

    /**
     * Сохраняет изображение в хранилище
     */
    public function save(): string
    {
        $this->file->storeAs($this->path, $this->fileName, ['disk' => 'files']);
        return $this->fileName;
    }
}