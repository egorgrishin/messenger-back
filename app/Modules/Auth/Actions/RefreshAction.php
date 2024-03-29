<?php
declare(strict_types=1);

namespace Modules\Auth\Actions;

use Core\Parents\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Dto\CreateRefreshTokenDto;
use Modules\Auth\Dto\RefreshDto;
use Modules\Auth\Models\RefreshToken;
use Modules\Auth\Tasks\CreateRefreshTokenTask;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
            throw new HttpException(401);
        }
        if ($refresh->is_blocked) {
            $this->blockTokenChain($refresh->chain);
            throw new HttpException(401);
        }
        if ($refresh->expired_in < now()) {
            $refresh->block();
            throw new HttpException(401);
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
