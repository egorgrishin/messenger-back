<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Services\User\Dto\CreateUserDto;
use App\Services\User\Models\User;
use Throwable;

final class CreateUserAction extends Action
{
    /**
     * Создает нового пользователя
     */
    public function run(CreateUserDto $dto): User
    {
        $this->validate($dto);

        try {
            return User::create($dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Проверяет данные DTO на корректность
     */
    private function validate(CreateUserDto $dto): void
    {
        $users = $this->getUsersForValidate($dto);
        if ($users->where('login', $dto->login)->isNotEmpty()) {
            throw new HttpException(422, 'Логин уже используется');
        }
        if ($users->where('nick', $dto->nick)->isNotEmpty()) {
            throw new HttpException(422, 'Имя аккаунта уже используется');
        }
        if ($dto->email && $users->where('email', $dto->email)->isNotEmpty()) {
            throw new HttpException(422, 'Адрес электронной почты уже используется');
        }
        if (!$dto->email && !$dto->codeWord) {
            throw new HttpException(422, 'Должен быть указан адрес электронной почты или кодовое слово');
        }
    }

    /**
     * Возвращает пользователей, у которых совпадает login, nick, или email
     * с указанными пользователем
     */
    private function getUsersForValidate(CreateUserDto $dto): Collection
    {
        return User::query()
            ->select([
                'login',
                'nick',
                'email',
            ])
            ->where('login', $dto->login)
            ->orWhere('nick', $dto->nick)
            ->when($dto->email, function (Builder $query) use ($dto) {
                $query->orWhere('email', $dto->email);
            })
            ->get();
    }
}
