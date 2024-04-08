<?php
declare(strict_types=1);

namespace Modules\Message\Actions;

use Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Modules\Message\Dto\GetChatMessagesDto;
use Modules\Message\Models\Message;

final class GetChatMessagesAction extends Action
{
    /**
     * Возвращает список сообщений чата
     */
    public function run(GetChatMessagesDto $dto): array
    {
        return Message::query()
            ->select([
                'id',
                'chat_id',
                'user_id',
                'text',
                'created_at',
            ])
            ->where('chat_id', $dto->chatId)
            ->when(
                $dto->startMessageId !== null,
                function (EloquentBuilder $query) use ($dto) {
                    $query->where('id', '<', $dto->startMessageId);
                },
            )
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->toArray();
    }
}
