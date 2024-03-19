<?php
declare(strict_types=1);

namespace Modules\User\Actions;

use Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Dto\DeleteUserFriendDto;
use Modules\User\Models\Friendship;
use Modules\User\Tasks\ExistsUserTask;
use Modules\User\Tasks\UserHasFriendTask;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class DeleteUserFriendAction extends Action
{
    /**
     * Удаляет пользователя из друзей
     */
    public function run(DeleteUserFriendDto $dto): void
    {
        if (!$this->task(ExistsUserTask::class)->run($dto->friendId)) {
            throw new HttpException(422);
        }
        if (!$this->task(UserHasFriendTask::class)->run($dto->userId, $dto->friendId)) {
            throw new HttpException(400);
        }

        $this->deleteFriendship($dto);
    }

    /**
     * Удаляет дружбу между пользователями
     */
    private function deleteFriendship(DeleteUserFriendDto $dto): void
    {
        Friendship::query()
            ->where(function (Builder $query) use ($dto) {
                $query->where('user_id', $dto->userId)
                    ->where('friend_id', $dto->friendId);
            })
            ->orWhere(function (Builder $query) use ($dto) {
                $query->where('friend_id', $dto->userId)
                    ->where('user_id', $dto->friendId);
            })
            ->delete();
    }
}
