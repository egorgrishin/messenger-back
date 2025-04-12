<?php

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\User\Dto\ResetPasswordDto;
use App\Services\User\Models\User;
use App\Services\User\Tasks\CheckCodeTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ResetPasswordAction extends Action
{
    public function run(ResetPasswordDto $dto): void
    {
        $user = $this->getUser($dto->email);
        $this->validate($user, $dto->code);

        try {
            $user->password = $dto->password;
            $user->saveOrFail();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Проверяет корректность кода подтверждения электронной почты
     */
    private function validate(?User $user, string $code): void
    {
        if (!$user) {
            throw new HttpException(422);
        }
        if (!$this->task(CheckCodeTask::class)->run($user->email, $code)) {
            throw new HttpException(422, 'Некорректный код подтверждения');
        }
    }

    /**
     * Возвращает пользователя по email
     */
    private function getUser(?string $email): ?User
    {
        if (Auth::hasUser()) {
            /** @var User */
            return Auth::user();
        }

        if (!$email) {
            return null;
        }

        /** @var ?User */
        return User::query()->where('email', $email)->first();
    }
}