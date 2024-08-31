<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use App\Services\User\Dto\GetUsersDto;
use App\Services\User\Models\User;
use Illuminate\Support\Collection;

final class GetUsersAction extends Action
{
    /**
     * Максимальное количество получаемых пользователей
     */
    private const LIMIT = 50;

    /**
     * Возвращает список пользователей по нику
     */
    public function run(GetUsersDto $dto): Collection
    {
        return User::query()
            ->select([
                'id',
                'nick',
            ])
            ->where('id', '<>', $dto->user_id)
            ->where('nick', 'like', "%$dto->nick%")
            ->when(
                $dto->startId !== null,
                function (Builder $query) use ($dto) {
                    $query->where('id', '<', $dto->startId);
                },
            )
            ->orderByDesc('id')
            ->limit(self::LIMIT)
            ->get();
    }
}
