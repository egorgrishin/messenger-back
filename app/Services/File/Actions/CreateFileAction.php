<?php
declare(strict_types=1);

namespace App\Services\File\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\File\Classes\Saver\Saver;
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
            $filename = Saver::getHandler($dto)->save();
            return $this->createFile($filename, $dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
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
