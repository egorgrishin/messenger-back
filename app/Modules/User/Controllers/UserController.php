<?php
declare(strict_types=1);

namespace Modules\User\Controllers;

use Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\Actions\CreateUserAction;
use Modules\User\Actions\FindUserAction;
use Modules\User\Actions\GetUsersAction;
use Modules\User\Requests\CreateUserRequest;
use Modules\User\Requests\FindUserRequest;
use Modules\User\Requests\GetUsersRequest;

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
        return $this->json($users);
    }

    /**
     * Возвращает список пользователей с фильтром по нику
     */
    public function find(FindUserRequest $request): JsonResponse
    {
        $user = $this->action(FindUserAction::class)->run(
            (int) $request->route('userId')
        );
        return $this->json($user);
    }

    /**
     * Создает нового пользователя
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $this->action(CreateUserAction::class)->run(
            $request->toDto()
        );
        return $this->json([], 201);
    }
}
