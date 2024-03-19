<?php
declare(strict_types=1);

namespace Modules\User\Tasks;

use Core\Parents\Task;
use Modules\User\Models\Friendship;

final class UserHasFriendTask extends Task
{
    /**
     * Проверяет, что 2 пользователя являются друзьями
     */
    public function run(int $userId, int $friendId): bool
    {
        return Friendship::query()
            ->where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->exists();
    }
}
