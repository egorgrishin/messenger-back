<?php
declare(strict_types=1);

namespace App\Services\Auth\Controllers;

use App\Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\Auth\Actions\AccessAction;
use App\Services\Auth\Actions\RefreshAction;
use App\Services\Auth\Requests\AccessRequest;
use App\Services\Auth\Requests\RefreshRequest;

final class AuthController extends Controller
{
    /**
     * Авторизация по учетным данным
     */
    public function access(AccessRequest $request): JsonResponse
    {
        [$accessToken, $refreshToken] = $this
            ->action(AccessAction::class)
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
