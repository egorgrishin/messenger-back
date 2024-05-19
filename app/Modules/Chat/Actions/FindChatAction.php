<?php
declare(strict_types=1);

namespace Modules\Chat\Actions;

use Core\Exceptions\HttpException;
use Core\Parents\Action;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Dto\FindChatDto;
use Modules\Chat\Models\Chat;
use Modules\Chat\Tasks\UserInChatTask;

final class FindChatAction extends Action
{
    /**
     * Возвращает чат по ID
     */
    public function run(FindChatDto $dto): array
    {
        if (!$this->task(UserInChatTask::class)->run($dto->chatId, $dto->userId)) {
            throw new HttpException(403, 'Вы не состоите в чате');
        }

        return Chat::query()
            ->select([
                'chats.id',
                'title',
                'is_dialog',
            ])
            ->with('users:users.id,nick')
            ->find($dto->chatId)
            ->toArray();
    }

    /**
     * Проверяет, что пользователь состоит в чате
     */
    private function userInChat(FindChatDto $dto): bool
    {
        return DB::table('chat_user')
            ->where('chat_id', $dto->chatId)
            ->where('user_id', $dto->userId)
            ->exists();
    }
}
