<?php
declare(strict_types=1);

namespace App\Modules\Auth\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Modules\Auth\Dto\CreateRefreshTokenDto;
use App\Modules\Auth\Dto\LoginDto;
use App\Modules\Auth\Tasks\CreateRefreshTokenTask;
use Throwable;

final class LoginAction extends Action
{
    /**
     * Авторизация по учетным данным
     */
    public function run(LoginDto $dto): array
    {
        $accessToken = $this->login($dto);
        try {
            $refresh = $this->task(CreateRefreshTokenTask::class)
                ->run(new CreateRefreshTokenDto(
                    null, Auth::id(), $dto->ipAddress, $dto->userAgent
                ));
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }

        return [$accessToken, $refresh->ulid];
    }

    /**
     * Авторизация с переданными данными
     */
    private function login(LoginDto $dto): string
    {
        /** @var string|false $accessToken */
        $accessToken = Auth::attempt([
            'nick'     => $dto->nick,
            'password' => $dto->password,
        ]);

        if ($accessToken === false) {
            throw new HttpException(401, 'Некорректные данные');
        }

        return $accessToken;
    }
}
