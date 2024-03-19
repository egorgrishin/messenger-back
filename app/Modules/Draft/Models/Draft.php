<?php
declare(strict_types=1);

namespace Modules\Draft\Models;

use Modules\Draft\Data\Factories\DraftFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $from_id
 * @property int $to_id
 * @property string|null $text
 * @property DateTimeInterface $updated_at
 */
final class Draft extends Model
{
    use HasFactory;

    public const CREATED_AT = null;

    protected static function newFactory(): DraftFactory
    {
        return DraftFactory::new();
    }
}
