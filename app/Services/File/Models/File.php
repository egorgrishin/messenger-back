<?php
declare(strict_types=1);

namespace App\Services\File\Models;

use App\Core\Parents\Model;
use App\Services\File\Classes\Saver\Document;
use App\Services\File\Classes\Saver\Saver;
use App\Services\File\Data\Factories\FileFactory;
use App\Services\File\Dto\CreateFileDto;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * @property string $uuid
 * @property int $user_id
 * @property string $filename
 * @property string $client_filename
 * @property int|null $message_id
 * @property int $type
 * @property string|null $url
 * @property string|null $video_preview_url
 * @property DateTimeInterface $created_at
 */
final class File extends Model
{
    use HasFactory;

    public const TYPE_IMAGE = 1;
    public const TYPE_VIDEO = 2;
    public const TYPE_DOCUMENT = 3;

    public const UPDATED_AT = null;

    protected $appends = [
        'url',
        'video_preview_url',
    ];

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }

    /** @noinspection PhpUnused */
    protected function url(): Attribute
    {
        $getter = function () {
            if (!$this->filename) {
                return null;
            }

            $path = sprintf('%s/%s/%s', $this->user_id, self::typeToString($this->type), $this->filename);
            return Storage::disk('files')->url($path);
        };

        return new Attribute(get: $getter);
    }

    /** @noinspection PhpUnused */
    protected function videoPreviewUrl(): Attribute
    {
        $getter = function () {
            if (!$this->filename || $this->type !== self::TYPE_VIDEO) {
                return null;
            }

            $path = sprintf('%s/%s/%s.jpg', $this->user_id, 'video_previews', $this->filename);
            return Storage::disk('files')->url($path);
        };

        return new Attribute(get: $getter);
    }

    /**
     * Сохраняет файл в хранилище и добавляет запись о нем в базу данных.
     * @throws Throwable
     */
    public static function create(CreateFileDto $dto): self
    {
        $saver = Saver::getHandler($dto);

        $file = new self();
        $file->uuid = $dto->uuid;
        $file->user_id = $dto->userId;
        $file->filename = $saver->save();
        $file->client_filename = $dto->file->getClientOriginalName();
        $file->type = $saver->getDatabaseType();
        $file->saveOrFail();

        return $file;
    }

    public static function typeToString(int $type): string
    {
        return Config::get('files.dirs', [])[$type] ?? Document::TYPE;
    }
}