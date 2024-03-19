<?php
declare(strict_types=1);

namespace Modules\Auth\Controllers;

use Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Auth\Actions\LoginAction;
use Modules\Auth\Actions\RefreshAction;
use Modules\Auth\Requests\LoginRequest;
use Modules\Auth\Requests\RefreshRequest;

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
