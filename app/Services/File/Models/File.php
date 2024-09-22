<?php
declare(strict_types=1);

namespace App\Services\File\Models;

use App\Core\Parents\Model;

/**
 * @property int $uuid
 * @property int $user_id
 * @property string $filename
 * @property string $sign
 */
final class File extends Model
{
    public const SIGN_BYTES = 16;

    public $timestamps = false;
}