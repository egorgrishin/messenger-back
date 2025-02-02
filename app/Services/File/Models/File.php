<?php
declare(strict_types=1);

namespace App\Services\File\Models;

use App\Core\Parents\Model;
use App\Services\File\Classes\Saver\Saver;
use App\Services\File\Data\Factories\FileFactory;
use App\Services\File\Dto\CreateFileDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Throwable;

/**
 * @property int $uuid
 * @property int $user_id
 * @property string $filename
 * @property int $type
 */
final class File extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }

    /**
     * Сохраняет файл в хранилище и добавляет запись о нем в базу данных.
     * @throws Throwable
     */
    public static function create(CreateFileDto $dto): self
    {
        $saver = Saver::getHandler($dto);

        $file = new self();
        $file->user_id = $dto->userId;
        $file->filename = $saver->save();
        $file->type = $saver->getDatabaseType();
        $file->saveOrFail();

        return $file;
    }
}