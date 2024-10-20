<?php
declare(strict_types=1);

namespace App\Services\User\Tasks;

use App\Core\Parents\Task;
use App\Services\User\Models\User;

class SaveOnlineTask extends Task
{
    /**
     * Устанавливает статус онлайна пользователей
     */
    public function run(array $userIds, bool $isOnline): void
    {
        User::query()
            ->whereIn('id', $userIds)
            ->update(['is_online' => $isOnline]);
    }
}