<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
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
        try {
            return User::create($dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }
}
