<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\User\Tasks\CheckCodeTask;
use Illuminate\Support\Facades\Log;
use App\Services\User\Dto\CreateUserDto;
use App\Services\User\Models\User;
use Illuminate\Support\Facades\Redis;
use Throwable;

final class CreateUserAction extends Action
{
    /**
     * Создает нового пользователя
     */
    public function run(CreateUserDto $dto): User
    {
        $this->validate($dto->email, $dto->code);

        try {
            return User::create($dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Проверяет корректность кода подтверждения электронной почты
     */
    private function validate(string $email, string $code): void
    {
        if (!$this->task(CheckCodeTask::class)->run($email, $code)) {
            throw new HttpException(422, 'Некорректный код подтверждения');
        }
    }
}
