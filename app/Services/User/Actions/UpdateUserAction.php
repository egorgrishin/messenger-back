<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\User\Dto\UpdateUserDto;
use App\Services\User\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserAction extends Action
{
    /**
     * Обновляет пользователя
     */
    public function run(UpdateUserDto $dto): User
    {
        try {
            $user = $this->getUserById($dto->id);
            $this->updateUser($user, $dto);
            return $user;
        } catch (ModelNotFoundException) {
            throw new HttpException(404, 'Пользователь не найден');
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Обновляет пользователя
     * @throws Throwable
     */
    private function updateUser(User $user, UpdateUserDto $dto): void
    {
        $user->nick = $dto->nick;
        $user->saveOrFail();
    }

    /**
     * Возвращает пользователя по ID
     * @throws ModelNotFoundException<User>
     */
    private function getUserById(int $userId): User
    {
        /** @var User */
        return User::query()->findOrFail($userId);
    }
}