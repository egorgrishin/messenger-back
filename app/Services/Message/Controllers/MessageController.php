<?php
declare(strict_types=1);

namespace App\Services\Message\Controllers;

use App\Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\Message\Actions\CreateMessageAction;
use App\Services\Message\Actions\GetChatMessagesAction;
use App\Services\Message\Requests\CreateMessageRequest;
use App\Services\Message\Requests\GetChatMessagesRequest;
use App\Services\Message\Resources\MessageResource;

final class MessageController extends Controller
{
    /**
     * Создает сообщение
     */
    public function create(CreateMessageRequest $request): JsonResponse
    {
        $message = $this->action(CreateMessageAction::class)->run(
            $request->toDto()
        );

        return $this
            ->resource($message, MessageResource::class)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Возвращает список сообщений чата
     */
    public function getChatMessages(GetChatMessagesRequest $request): JsonResponse
    {
        $messages = $this->action(GetChatMessagesAction::class)->run(
            $request->toDto()
        );

        return $this
            ->collection($messages, MessageResource::class)
            ->response();
    }
}
