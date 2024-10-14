<?php
declare(strict_types=1);

namespace App\Services\Chat\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\Chat\Dto\FindChatDto;
use App\Services\Chat\Models\Chat;
use App\Services\Chat\Tasks\UserInChatTask;

final class FindChatAction extends Action
{
    /**
     * Возвращает чат по ID
     */
    public function run(FindChatDto $dto): Chat
    {
        if (!$this->task(UserInChatTask::class)->run($dto->chatId, $dto->userId)) {
            throw new HttpException(403, 'Вы не состоите в чате');
        }

        /** @var Chat */
        return Chat::query()
            ->select([
                'chats.id',
                'title',
                'is_dialog',
            ])
            ->with('users:users.id,nick')
            ->find($dto->chatId);
    }
}
