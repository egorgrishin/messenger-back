<?php
declare(strict_types=1);

namespace App\Services\File\Models;

use App\Core\Parents\Model;

/**
 * @property int $uuid
 * @property int $user_id
 * @property string $filename
 */
final class File extends Model
{
    public $timestamps = false;
}