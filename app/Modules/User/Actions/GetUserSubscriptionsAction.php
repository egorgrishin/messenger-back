<?php
declare(strict_types=1);

namespace Modules\User\Actions;

use Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Models\User;

final class GetUserSubscriptionsAction extends Action
{
    /**
     * Возвращает список подписок пользователя
     */
    public function run(int $userId): array
    {
        return User::query()
            ->select([
                'id',
                'nick',
            ])
            ->whereHas('subscribers', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get()
            ->toArray();
    }
}
