<?php
declare(strict_types=1);

namespace App\Modules\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Support\Facades\Log;
use App\Modules\User\Dto\CreateUserDto;
use App\Modules\User\Models\User;
use Throwable;

final class CreateUserAction extends Action
{
    /**
     * Создает нового пользователя
     */
    public function run(CreateUserDto $dto): array
    {
        try {
            $user = new User();
            $user->nick = $dto->nick;
            $user->password = $dto->password;
            $user->saveOrFail();
            return $user->toArray();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }
}
