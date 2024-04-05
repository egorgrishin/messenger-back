<?php
declare(strict_types=1);

namespace Modules\Chat\Models;

use Core\Parents\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Message\Models\Message;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property string|null $title
 * @property bool $is_dialog
 * @property int $last_message_id
 */
final class Chat extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_dialog' => 'boolean',
        ];
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(
            Message::class,
            'id',
            'last_message_id',
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
