<?php
declare(strict_types=1);

namespace App\Services\User\Controllers;

use App\Core\Parents\Controller;
use App\Services\User\Actions\GetPasswordResetMethodsAction;
use App\Services\User\Actions\UpdateUserAction;
use App\Services\User\Requests\GetPasswordResetMethodsRequest;
use App\Services\User\Requests\UpdateUserRequest;
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
     * Возвращает способы, которыми пользователь может восстановить пароль от аккаунта.
     * Если доступно восстановление по электронному письму, то также вернется и адрес электронной почту, закрытый звездочками.
     * Если доступно восстановление по кодовому слову, то также вернется подсказка к нему.
     */
    public function getPasswordResetMethods(GetPasswordResetMethodsRequest $request): JsonResponse
    {
        $methods = $this->action(GetPasswordResetMethodsAction::class)->run(
            $request->routeUserId()
        );

        return response()->json([
            'data' => $methods,
        ]);
    }
}
