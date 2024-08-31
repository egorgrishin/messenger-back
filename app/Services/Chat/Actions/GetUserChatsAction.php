<?php
declare(strict_types=1);

namespace App\Services\Chat\Actions;

use App\Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use App\Services\Chat\Dto\GetUserChatsDto;
use App\Services\Chat\Models\Chat;

final class GetUserChatsAction extends Action
{
    /**
     * Максимальное количество получаемых чатов
     */
    private const LIMIT = 50;

    /**
     * Возвращает список чатов пользователя
     */
    public function run(GetUserChatsDto $dto): array
    {
        return Chat::query()
            ->select([
                'chats.id',
                'title',
                'is_dialog',
                'last_message_id',
            ])
            ->when(
                $dto->startMessageId !== null,
                function (EloquentBuilder $query) use ($dto) {
                    $query->where('last_message_id', '<', $dto->startMessageId);
                },
            )
            ->whereExists(function (Builder $query) use ($dto) {
                $query->selectRaw(1)
                    ->from('chat_user')
                    ->whereColumn('chats.id', 'chat_user.chat_id')
                    ->where('user_id', $dto->userId);
            })
            ->with([
                'users:users.id,nick',
                'lastMessage',
            ])
            ->orderByDesc('last_message_id')
            ->limit(self::LIMIT)
            ->get()
            ->toArray();
    }
}
