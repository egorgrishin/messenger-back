<?php
declare(strict_types=1);

namespace App\Services\Message\Models;

use App\Services\Chat\Models\Chat;
use App\Services\File\Models\File;
use App\Services\Message\Data\Factories\MessageFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string|null $text
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

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
