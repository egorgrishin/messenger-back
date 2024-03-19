<?php
declare(strict_types=1);

namespace Modules\User\Actions;

use Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Models\User;

final class GetUserSubscribersAction extends Action
{
    /**
     * Возвращает список подписчиков пользователя
     */
    public function run(int $userId): array
    {
        return User::query()
            ->select([
                'id',
                'nick',
            ])
            ->whereHas('subscriptions', function (Builder $query) use ($userId) {
                $query->where('friend_id', $userId);
            })
            ->get()
            ->toArray();
    }
}
