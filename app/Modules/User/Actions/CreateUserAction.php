<?php
declare(strict_types=1);

namespace Modules\User\Actions;

use Core\Parents\Action;
use Illuminate\Support\Facades\Log;
use Modules\User\Dto\CreateUserDto;
use Modules\User\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class CreateUserAction extends Action
{
    /**
     * Создает нового пользователя
     */
    public function run(CreateUserDto $dto): void
    {
        try {
            $user = new User();
            $user->nick = $dto->nick;
            $user->password = $dto->password;
            $user->saveOrFail();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }
}
