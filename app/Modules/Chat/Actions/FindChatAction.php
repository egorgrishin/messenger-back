<?php
declare(strict_types=1);

namespace App\Modules\Chat\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Support\Facades\DB;
use App\Modules\Chat\Dto\FindChatDto;
use App\Modules\Chat\Models\Chat;
use App\Modules\Chat\Tasks\UserInChatTask;

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
