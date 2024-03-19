<?php
declare(strict_types=1);

namespace Modules\User\Actions;

use Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Models\User;

final class GetUserFriendsAction extends Action
{
    /**
     * Возвращает список друзей пользователя
     */
    public function run(int $user_id): array
    {
        return User::query()
            ->select([
                'id',
                'nick',
            ])
            ->whereHas('friendRelations', function (Builder $query) use ($user_id) {
                $query->where('friend_id', $user_id);
            })
            ->get()
            ->toArray();
    }
}
