<?php
declare(strict_types=1);

namespace App\Services\Auth\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Auth\Dto\CreateRefreshTokenDto;
use App\Services\Auth\Dto\RefreshDto;
use App\Services\Auth\Models\RefreshToken;
use App\Services\Auth\Tasks\CreateRefreshTokenTask;
use Throwable;

final class RefreshAction extends Action
{
    /**
     * Продлевает Refresh Token и обновляет Access Token
     */
    public function run(RefreshDto $dto): array
    {
        $refresh = $this->getRefreshToken($dto->ulid);
        $this->validate($refresh);
        $refresh->block();

        try {
            $refresh = $this->task(CreateRefreshTokenTask::class)
                ->run(new CreateRefreshTokenDto(
                    $refresh->chain, $refresh->user_id, $refresh->ip_address, $refresh->user_agent
                ));
            $accessToken = Auth::loginUsingId($refresh->user_id);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }

        return [$accessToken, $refresh->ulid];
    }

    /**
     * Возвращает модель Refresh Token по ulid
     */
    private function getRefreshToken(string $ulid): ?RefreshToken
    {
        /** @var RefreshToken|null */
        return RefreshToken::query()->find($ulid);
    }

    /**
     * Проверяет токен на существование, блокировку и время жизни
     */
    private function validate(?RefreshToken $refresh): void
    {
        if ($refresh === null) {
            throw new HttpException(401, 'Сессия не найдена');
        }
        if ($refresh->is_blocked) {
            $this->blockTokenChain($refresh->chain);
            throw new HttpException(401, 'Сессия была заблокирована');
        }
        if ($refresh->expired_in < now()) {
            $refresh->block();
            throw new HttpException(401, 'Срок действия токена истек');
        }
    }

    /**
     * Блокирует семейство токенов
     */
    public function blockTokenChain(string $chain): void
    {
        RefreshToken::query()
            ->where('chain', $chain)
            ->update([
                'is_blocked' => 1,
            ]);
    }
}
