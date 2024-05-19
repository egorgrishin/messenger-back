<?php
declare(strict_types=1);

namespace App\Modules\Auth\Controllers;

use App\Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use App\Modules\Auth\Actions\LoginAction;
use App\Modules\Auth\Actions\RefreshAction;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RefreshRequest;

final class AuthController extends Controller
{
    /**
     * Авторизация по учетным данным
     */
    public function login(LoginRequest $request): JsonResponse
    {
        [$accessToken, $refreshToken] = $this
            ->action(LoginAction::class)
            ->run($request->toDto());

        return $this->json([
            'accessToken'  => $accessToken,
            'refreshToken' => $refreshToken,
        ]);
    }

    /**
     * Продлевает Refresh Token и обновляет Access Token
     */
    public function refresh(RefreshRequest $request): JsonResponse
    {
        [$accessToken, $refreshToken] = $this
            ->action(RefreshAction::class)
            ->run($request->toDto());

        return $this->json([
            'accessToken'  => $accessToken,
            'refreshToken' => $refreshToken,
        ]);
    }
}
