<?php
declare(strict_types=1);

namespace App\Services\Chat\Controllers;

use App\Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\Chat\Actions\CreateChatAction;
use App\Services\Chat\Actions\FindChatAction;
use App\Services\Chat\Actions\GetUserChatsAction;
use App\Services\Chat\Requests\CreateChatRequest;
use App\Services\Chat\Requests\FindChatRequest;
use App\Services\Chat\Requests\GetUserChatsRequest;
use App\Services\Chat\Resources\ChatResource;

final class ChatController extends Controller
{
    /**
     * Создает новый чат
     */
    public function create(CreateChatRequest $request): JsonResponse
    {
        [$isCreated, $chat] = $this->action(CreateChatAction::class)->run(
            $request->input('recipientId'),
        );

        return response()->json([
            'data' => [
                'isCreated' => $isCreated,
                'chat'      => (new ChatResource($chat))
            ],
        ], $isCreated ? 201 : 200);
    }

    /**
     * Возвращает чат по ID
     */
    public function find(FindChatRequest $request): JsonResponse
    {
        $chat = $this->action(FindChatAction::class)->run(
            (int) $request->route('chatId'),
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
