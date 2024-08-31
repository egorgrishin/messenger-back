<?php
declare(strict_types=1);

namespace App\Services\User\Controllers;

use App\Core\Parents\Controller;
use App\Services\User\Actions\DeleteUserAvatarAction;
use App\Services\User\Actions\UpdateUserAvatarAction;
use App\Services\User\Requests\DeleteUserAvatarRequest;
use App\Services\User\Requests\UpdateUserAvatarRequest;
use Illuminate\Http\JsonResponse;

class UserAvatarController extends Controller
{
    public function update(UpdateUserAvatarRequest $request): JsonResponse
    {
        $this->action(UpdateUserAvatarAction::class)->run(
            $request->validated('avatar')
        );

        return response()->json([], 204);
    }

    public function delete(DeleteUserAvatarRequest $request): JsonResponse
    {
        $this->action(DeleteUserAvatarAction::class)->run();
        return response()->json([], 204);
    }
}