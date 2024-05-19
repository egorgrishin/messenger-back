<?php
declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use App\Modules\User\Actions\CreateUserAction;
use App\Modules\User\Actions\GetUsersAction;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\Requests\GetUsersRequest;
use App\Modules\User\Resources\UserResource;

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
}
