<?php
declare(strict_types=1);

namespace Modules\User\Actions;

use Core\Parents\Action;
use Modules\User\Dto\GetUsersDto;
use Modules\User\Models\User;

final class GetUsersAction extends Action
{
    /**
     * Возвращает список пользователей по нику
     */
    public function run(GetUsersDto $dto): array
    {
        return User::query()
            ->select([
                'id',
                'nick',
            ])
            ->where('id', '<>', $dto->user_id)
            ->where('nick', 'like', "%$dto->nick%")
            ->get()
            ->toArray();
    }
}
