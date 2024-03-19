<?php
declare(strict_types=1);

namespace Modules\User\Controllers;

use Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\Actions\AddUserFriendAction;
use Modules\User\Actions\CreateUserAction;
use Modules\User\Actions\DeleteUserFriendAction;
use Modules\User\Actions\GetUserFriendsAction;
use Modules\User\Actions\GetUsersAction;
use Modules\User\Actions\GetUserSubscribersAction;
use Modules\User\Actions\GetUserSubscriptionsAction;
use Modules\User\Requests\AddUserFriendRequest;
use Modules\User\Requests\CreateUserRequest;
use Modules\User\Requests\DeleteUserFriendRequest;
use Modules\User\Requests\GetUserFriendsRequest;
use Modules\User\Requests\GetUsersRequest;
use Modules\User\Requests\GetUserSubscribersRequest;
use Modules\User\Requests\GetUserSubscriptionsRequest;

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
     * Создает нового пользователя
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $this->action(CreateUserAction::class)->run(
            $request->toDto()
        );
        return $this->json([], 201);
    }

    /**
     * @param GetUserFriendsRequest $request
     * @return JsonResponse
     */
    public function getUserFriends(GetUserFriendsRequest $request): JsonResponse
    {
        $friends = $this->action(GetUserFriendsAction::class)->run(
            (int) $request->route('userId')
        );

        return $this->json($friends);
    }

    public function getUserSubscriptions(GetUserSubscriptionsRequest $request): JsonResponse
    {
        $subscribers = $this->action(GetUserSubscriptionsAction::class)->run(
            (int) $request->route('userId')
        );
        return $this->json($subscribers);
    }

    public function getUserSubscribers(GetUserSubscribersRequest $request): JsonResponse
    {
        $subscribers = $this->action(GetUserSubscribersAction::class)->run(
            (int) $request->route('userId')
        );
        return $this->json($subscribers);
    }

    public function addUserFriend(AddUserFriendRequest $request): JsonResponse
    {
        $this->action(AddUserFriendAction::class)->run(
            $request->toDto()
        );
        return $this->json([], 204);
    }

    public function deleteUserFriend(DeleteUserFriendRequest $request): JsonResponse
    {
        $this->action(DeleteUserFriendAction::class)->run(
            $request->toDto()
        );
        return $this->json([], 204);
    }
}
