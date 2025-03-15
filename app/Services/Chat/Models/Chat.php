<?php
declare(strict_types=1);

namespace App\Services\Chat\Models;

use App\Core\Parents\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Services\Chat\Data\Factories\ChatFactory;
use App\Services\Chat\Events\ChatUpdated;
use App\Services\Message\Models\Message;
use App\Services\User\Models\User;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string|null $title
 * @property bool $is_dialog
 * @property int $last_message_id
 * @property Collection $users
 */
final class Chat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_dialog' => 'boolean',
        ];
    }

    protected static function newFactory(): ChatFactory
    {
        return ChatFactory::new();
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

    /**
     * Прикрепляет пользователей к чату
     */
    public function attachUsers(array $users): self
    {
        $this->users()->attach($users);
        foreach ($users as $userId) {
            ChatUpdated::dispatch($userId, $this);
        }
        return $this;
    }
}
