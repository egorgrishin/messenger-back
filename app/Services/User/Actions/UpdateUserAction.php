<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\User\Dto\UpdateUserDto;
use App\Services\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserAction extends Action
{
    /**
     * Обновляет пользователя
     */
    public function run(UpdateUserDto $dto): User
    {
        $this->validate($dto);

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
     * Проверяет данные DTO на корректность
     */
    private function validate(UpdateUserDto $dto): void
    {
        $users = $this->getUsersForValidate($dto);
        if ($users->where('nick', $dto->nick)->isNotEmpty()) {
            throw new HttpException(422, 'Имя аккаунта уже используется');
        }
        if ($dto->email && $users->where('email', $dto->email)->isNotEmpty()) {
            throw new HttpException(422, 'Адрес электронной почты уже используется');
        }
        if ($dto->shortLink && $users->where('short_link', $dto->shortLink)->isNotEmpty()) {
            throw new HttpException(422, 'Короткая ссылка уже используется');
        }
        if (!$dto->email && !$dto->codeWord) {
            throw new HttpException(422, 'Должен быть указан адрес электронной почты или кодовое слово');
        }
    }

    /**
     * Возвращает пользователей, у которых совпадает nick, email или short_link
     * с указанными пользователем
     */
    private function getUsersForValidate(UpdateUserDto $dto): Collection
    {
        return User::query()
            ->select([
                'nick',
                'email',
                'short_link',
            ])
            ->where('id', '<>', $dto->id)
            ->where('nick', $dto->nick)
            ->when($dto->email, function (Builder $query) use ($dto) {
                $query->orWhere('email', $dto->email);
            })
            ->when($dto->shortLink, function (Builder $query) use ($dto) {
                $query->orWhere('short_link', $dto->shortLink);
            })
            ->get();
    }


    /**
     * Обновляет пользователя
     * @throws Throwable
     */
    private function updateUser(User $user, UpdateUserDto $dto): void
    {
        $user->nick = $dto->nick;
        $user->status = $dto->status;
        $user->short_link = $dto->shortLink;
        $user->email = $dto->email;
        $user->code_word = $dto->codeWord;
        $user->code_hint = $dto->codeHint;
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