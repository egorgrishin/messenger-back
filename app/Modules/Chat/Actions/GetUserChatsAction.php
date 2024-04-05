<?php
declare(strict_types=1);

namespace Modules\Chat\Actions;

use Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Modules\Chat\Dto\GetUserChatsDto;
use Modules\Chat\Models\Chat;

final class GetUserChatsAction extends Action
{
    /**
     * Максимальное количество получаемых чатов
     */
    private const LIMIT = 25;

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
                'users' => function (BelongsToMany $query) use ($dto) {
                    $query
                        ->select([
                            'users.id',
                            'nick',
                        ])
                        ->where('users.id', '<>', $dto->userId);
                },
                'lastMessage' => function (HasOne $query) {
                    $query->select([
                        'messages.id',
                        'chat_id',
                        'user_id',
                        'text',
                        'created_at',
                    ]);
                },
            ])
            ->orderByDesc('last_message_id')
            ->limit(self::LIMIT)
            ->get()
            ->toArray();
    }
}
