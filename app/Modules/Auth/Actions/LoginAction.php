<?php
declare(strict_types=1);

namespace Modules\Auth\Actions;

use Core\Parents\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Dto\CreateRefreshTokenDto;
use Modules\Auth\Dto\LoginDto;
use Modules\Auth\Tasks\CreateRefreshTokenTask;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class LoginAction extends Action
{
    /**
     * Авторизация по учетным данным
     */
    public function run(LoginDto $dto): array
    {
        $accessToken = Auth::attempt([
            'nick'     => $dto->nick,
            'password' => $dto->password,
        ]);

        if ($accessToken === false) {
            throw new HttpException(401);
        }

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
}
