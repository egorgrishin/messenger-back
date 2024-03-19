<?php
declare(strict_types=1);

namespace Modules\Auth\Tasks;

use Core\Parents\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Auth\Dto\CreateRefreshTokenDto;
use Modules\Auth\Models\RefreshToken;
use Throwable;

final class CreateRefreshTokenTask extends Task
{
    /**
     * Создает новый Refresh Token и сохраняет его в базу данных
     * @throws Throwable
     */
    public function run(CreateRefreshTokenDto $dto): RefreshToken
    {
        $refresh = new RefreshToken();
        $refresh->ulid = Str::ulid()->toBase32();
        $refresh->chain = $dto->chain ?: Str::ulid()->toBase32();
        $refresh->user_id = $dto->userId;
        $refresh->ip_address = $dto->ipAddress;
        $refresh->user_agent = $dto->userAgent;
        $refresh->expired_in = time() + env('REFRESH_TOKEN_LIFETIME');
        $refresh->saveOrFail();

        return $refresh;
    }
}
