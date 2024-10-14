<?php
declare(strict_types=1);

namespace App\Services\File\Models;

use App\Core\Parents\Model;
use App\Services\File\Data\Factories\FileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $uuid
 * @property int $user_id
 * @property string $filename
 */
final class File extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }
}