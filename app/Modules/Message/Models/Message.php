<?php
declare(strict_types=1);

namespace Modules\Message\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Message\Data\Factories\MessageFactory;

/**
 * @property int $id
 * @property int $from_id
 * @property int $to_id
 * @property string $text
 * @property DateTimeInterface $created_at
 */
final class Message extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected static function newFactory(): MessageFactory
    {
        return MessageFactory::new();
    }
}
