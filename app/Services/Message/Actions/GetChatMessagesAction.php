<?php
declare(strict_types=1);

namespace App\Services\Message\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use App\Services\Chat\Tasks\UserInChatTask;
use App\Services\Message\Dto\GetChatMessagesDto;
use App\Services\Message\Models\Message;
use Illuminate\Support\Collection;

final class GetChatMessagesAction extends Action
{
    /**
     * Максимальное количество получаемых сообщений
     */
    private const LIMIT = 100;

    /**
     * Возвращает список сообщений чата
     */
    public function run(GetChatMessagesDto $dto): Collection
    {
        if (!$this->task(UserInChatTask::class)->run($dto->chatId, $dto->userId)) {
            throw new HttpException(403, 'Вы не состоите в чате');
        }

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
                $dto->startId !== null,
                function (EloquentBuilder $query) use ($dto) {
                    $query->where('id', '<', $dto->startId);
                },
            )
            ->with('files')
            ->orderByDesc('id')
            ->limit(self::LIMIT)
            ->get();
    }
}
