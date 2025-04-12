<?php
declare(strict_types=1);

namespace App\Services\Chat\Actions;

use App\Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Services\Chat\Dto\GetUserChatsDto;
use App\Services\Chat\Models\Chat;
use Illuminate\Support\Collection;

final class GetUserChatsAction extends Action
{
    /**
     * Максимальное количество получаемых чатов
     */
    private const LIMIT = 50;

    /**
     * Возвращает список чатов пользователя
     */
    public function run(GetUserChatsDto $dto): Collection
    {
        return Chat::query()
            ->select([
                'chats.id',
                'last_message_id',
            ])
            ->when(
                $dto->startMessageId !== null,
                function (Builder $query) use ($dto) {
                    $query->where('last_message_id', '<', $dto->startMessageId);
                },
            )
            ->whereHas('users', function (Builder $query) use ($dto) {
                $query->where('users.id', $dto->userId);
            })
            ->with([
                'users:users.id,nick,avatar_filename',
                'lastMessage' => function (HasOne $query) {
                    $query->withCount('files');
                },
            ])
            ->orderByDesc('last_message_id')
            ->limit(self::LIMIT)
            ->get();
    }
}
