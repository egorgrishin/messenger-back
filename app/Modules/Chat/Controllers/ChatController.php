<?php
declare(strict_types=1);

namespace Modules\Chat\Controllers;

use Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Chat\Actions\GetUserChatsAction;
use Modules\Chat\Requests\GetUserChatsRequest;

final class ChatController extends Controller
{
    /**
     * Возвращает список чатов пользователя
     */
    public function getUserChats(GetUserChatsRequest $request): JsonResponse
    {
        $chats = $this->action(GetUserChatsAction::class)->run(
            $request->toDto()
        );
        return $this->json($chats);
    }
}
