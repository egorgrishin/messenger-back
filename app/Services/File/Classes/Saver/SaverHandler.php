<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Saver;

use App\Services\File\Dto\CreateFileDto;

interface SaverHandler
{
    public function __construct(CreateFileDto $dto);

    /**
     * Сохраняет файл в хранилище
     */
    public function save(): string;
}
