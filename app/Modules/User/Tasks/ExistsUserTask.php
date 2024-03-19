<?php
declare(strict_types=1);

namespace Modules\User\Tasks;

use Core\Parents\Task;
use Modules\User\Models\User;

final class ExistsUserTask extends Task
{
    /**
     * Проверяет существование пользователя
     */
    public function run(int $userId): bool
    {
        return User::query()
            ->where('id', $userId)
            ->exists();
    }
}
