<?php
declare(strict_types=1);

namespace App\Services\Auth\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Auth\Dto\CreateRefreshTokenDto;
use App\Services\Auth\Dto\AccessDto;
use App\Services\Auth\Tasks\CreateRefreshTokenTask;
use Throwable;

final class AccessAction extends Action
{
    /**
     * Авторизация по учетным данным. Возвращает access и refresh токены
     */
    public function run(AccessDto $dto): array
    {
        $accessToken = $this->login($dto);
        try {
            $refresh = $this
                ->task(CreateRefreshTokenTask::class)
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
    private function login(AccessDto $dto): string
    {
        /** @var string|false $accessToken */
        $accessToken = Auth::attempt([
            'email'    => $dto->email,
            'password' => $dto->password,
        ]);

        if ($accessToken === false) {
            throw new HttpException(401, 'Некорректные данные');
        }

        return $accessToken;
    }
}
