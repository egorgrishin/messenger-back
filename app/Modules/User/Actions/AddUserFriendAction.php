<?php
declare(strict_types=1);

namespace Modules\User\Actions;

use Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\User\Dto\AddUserFriendDto;
use Modules\User\Models\Friendship;
use Modules\User\Tasks\ExistsUserTask;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AddUserFriendAction extends Action
{
    /**
     * Отправляет пользователю запрос в друзья
     */
    public function run(AddUserFriendDto $dto): void
    {
        /*
         * Мы отправляем запрос в друзья от userId к friendId
         *
         * Общие условия:
         * 1) userId !== friendId
         * 2) Пользователи существуют
         * 3) В базе нет запроса от userId к friendId
         *
         * Если в базе нет запроса от friendId к userId
         * То создаем заявку от userId к friendId с is_accepted = 0
         *
         * Если в базе есть запрос от friendId к userId
         * То принимаем запрос в друзья. В запросе от friendId к userId ставим is_accepted = 1
         */

        if ($dto->userId === $dto->friendId) {
            throw new HttpException(422);
        }
        if (!$this->task(ExistsUserTask::class)->run($dto->friendId)) {
            throw new HttpException(422);
        }

        $friendships = $this->getFriendships($dto);
        if ($this->isUserOfferedFriendship($friendships, $dto)) {
            throw new HttpException(400);
        }

        ($friendship = $this->fetchFriendshipOffer($friendships, $dto)) === null
            ? $this->createFriendship($dto)
            : $friendship->accept();
    }

    /**
     * Возвращает отношения между пользователями
     */
    private function getFriendships(AddUserFriendDto $dto): Collection
    {
        return Friendship::query()
            ->where(function (Builder $query) use ($dto) {
                $query->where('user_id', $dto->userId)
                    ->where('friend_id', $dto->friendId);
            })
            ->orWhere(function (Builder $query) use ($dto) {
                $query->where('friend_id', $dto->userId)
                    ->where('user_id', $dto->friendId);
            })
            ->get();
    }

    /**
     * Проверяет, предлагал ли пользователь дружбу
     */
    private function isUserOfferedFriendship(Collection $friendships, AddUserFriendDto $dto): bool
    {
        return $friendships
            ->where('user_id', $dto->userId)
            ->where('friend_id', $dto->friendId)
            ->isNotEmpty();
    }

    /**
     * Возвращает предложение дружбы пользователю
     */
    private function fetchFriendshipOffer(Collection $friendships, AddUserFriendDto $dto): ?Friendship
    {
        return $friendships
            ->where('friend_id', $dto->userId)
            ->where('user_id', $dto->friendId)
            ->first();
    }

    /**
     * Создает запрос на добавление в друзья от user к friend
     */
    private function createFriendship(AddUserFriendDto $dto): void
    {
        $friendship = new Friendship();
        $friendship->user_id = $dto->userId;
        $friendship->friend_id = $dto->friendId;
        $friendship->save();
    }
}
