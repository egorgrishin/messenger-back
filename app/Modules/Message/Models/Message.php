<?php
declare(strict_types=1);

namespace App\Modules\Message\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Chat\Models\Chat;
use App\Modules\Message\Data\Factories\MessageFactory;

/**
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string $text
 * @property DateTimeInterface $created_at
 * @property Chat $chat
 */
final class Message extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected static function newFactory(): MessageFactory
    {
        return MessageFactory::new();
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
