<?php
declare(strict_types=1);

namespace App\Services\File\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\File\Dto\CreateFileDto;
use App\Services\File\Models\File;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CreateFileAction extends Action
{
    /**
     * Сохраняет файл в хранилище и информацию о нем в базе данных
     */
    public function run(CreateFileDto $dto): File
    {
        try {
            return File::create($dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }
}
