<?php
declare(strict_types=1);

namespace App\Services\Chat\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\Chat\Models\Chat;
use App\Services\Chat\Tasks\UserInChatTask;
use Illuminate\Support\Facades\Auth;

final class FindChatAction extends Action
{
    /**
     * Возвращает чат по ID
     */
    public function run(int $chatId): Chat
    {
        if (!$this->task(UserInChatTask::class)->run($chatId, Auth::id())) {
            throw new HttpException(403, 'Вы не состоите в чате');
        }

        /** @var Chat */
        return Chat::query()
            ->select('chats.id')
            ->with('users:users.id,nick,avatar_filename')
            ->find($chatId);
    }
}
