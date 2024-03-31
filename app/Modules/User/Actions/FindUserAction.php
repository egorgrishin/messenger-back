<?php
declare(strict_types=1);

namespace Modules\User\Actions;

use Core\Parents\Action;
use Modules\User\Models\User;

final class FindUserAction extends Action
{
    /**
     * Возвращает пользователя по ID
     */
    public function run(int $userId): array
    {
        return User::query()->find($userId)->toArray();
    }
}
