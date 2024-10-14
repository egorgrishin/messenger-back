<?php
declare(strict_types=1);

namespace App\Services\File\Classes\Saver;

use App\Services\File\Dto\CreateFileDto;

class Saver
{
    /**
     * Возвращает объект SaverHandler
     */
    public static function getHandler(CreateFileDto $dto): SaverHandler
    {
        return new Image($dto);
    }
}
