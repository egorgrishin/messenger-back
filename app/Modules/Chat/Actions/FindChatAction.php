<?php
declare(strict_types=1);

namespace Modules\Chat\Actions;

use Core\Parents\Action;
use Modules\Chat\Dto\FindChatDto;
use Modules\Chat\Models\Chat;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FindChatAction extends Action
{
    /**
     * Возвращает чат по ID
     */
    public function run(FindChatDto $dto): array
    {
        $chat = Chat::query()
            ->select([
                'chats.id',
                'title',
                'is_dialog',
            ])
            ->with([
                'users:users.id,nick',
            ])
            ->find($dto->chatId);

        if (!$chat) {
            throw new HttpException(404);
        }

        return $chat->toArray();
    }
}
