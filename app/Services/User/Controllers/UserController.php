<?php
declare(strict_types=1);

namespace App\Services\User\Controllers;

use App\Core\Parents\Controller;
use App\Services\User\Actions\ResetPasswordAction;
use App\Services\User\Actions\SendCodeAction;
use App\Services\User\Actions\UpdateUserAction;
use App\Services\User\Requests\ResetPasswordRequest;
use App\Services\User\Requests\UpdateUserRequest;
use App\Services\User\Requests\SendCodeRequest;
use Illuminate\Http\JsonResponse;
use App\Services\User\Actions\CreateUserAction;
use App\Services\User\Actions\GetUsersAction;
use App\Services\User\Requests\CreateUserRequest;
use App\Services\User\Requests\GetUsersRequest;
use App\Services\User\Resources\UserResource;

final class UserController extends Controller
{
    /**
     * Возвращает список пользователей с фильтром по нику
     */
    public function get(GetUsersRequest $request): JsonResponse
    {
        $users = $this->action(GetUsersAction::class)->run(
            $request->toDto()
        );

        return $this
            ->collection($users, UserResource::class)
            ->response();
    }

    /**
     * Создает нового пользователя
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $user = $this->action(CreateUserAction::class)->run(
            $request->toDto()
        );

        return $this
            ->resource($user, UserResource::class)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Создает нового пользователя
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->action(UpdateUserAction::class)->run(
            $request->toDto()
        );

        return $this
            ->resource($user, UserResource::class)
            ->response();
    }

    /**
     * Отправляет код подтверждения email на почту
     */
    public function sendVerificationCode(SendCodeRequest $request): JsonResponse
    {
        $retry = $this->action(SendCodeAction::class)->run(
            $request->validated('email')
        );

        return response()->json([
            'data' => [
                'retry' => $retry
            ],
        ], 201);
    }

    /**
     * Сбрасывает пароль от аккаунта пользователя
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $this->action(ResetPasswordAction::class)->run(
            $request->toDto()
        );

        return response()->json();
    }
}
