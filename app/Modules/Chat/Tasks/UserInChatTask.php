<?php
declare(strict_types=1);

namespace App\Modules\Chat\Tasks;

use App\Core\Parents\Task;
use Illuminate\Support\Facades\DB;

final class UserInChatTask extends Task
{
    /**
     * Проверяет, что пользователь состоит в чате
     */
    public function run(int $chatId, int $userId): bool
    {
        return DB::table('chat_user')
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->exists();
    }
}