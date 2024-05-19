<?php
declare(strict_types=1);

namespace Modules\Chat\Controllers;

use Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Chat\Actions\CreateChatAction;
use Modules\Chat\Actions\FindChatAction;
use Modules\Chat\Actions\GetUserChatsAction;
use Modules\Chat\Requests\CreateChatRequest;
use Modules\Chat\Requests\FindChatRequest;
use Modules\Chat\Requests\GetUserChatsRequest;
use Modules\Chat\Resources\ChatResource;

final class ChatController extends Controller
{
    /**
     * Создает новый чат
     */
    public function create(CreateChatRequest $request): JsonResponse
    {
        $chat = $this->action(CreateChatAction::class)->run(
            $request->toDto()
        );

        return $this
            ->resource($chat, ChatResource::class)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Возвращает чат по ID
     */
    public function find(FindChatRequest $request): JsonResponse
    {
        $chat = $this->action(FindChatAction::class)->run(
            $request->toDto()
        );

        return $this
            ->resource($chat, ChatResource::class)
            ->response();
    }

    /**
     * Возвращает список чатов пользователя
     */
    public function getUserChats(GetUserChatsRequest $request): JsonResponse
    {
        $chats = $this->action(GetUserChatsAction::class)->run(
            $request->toDto()
        );

        return $this
            ->collection($chats, ChatResource::class)
            ->response();
    }
}
