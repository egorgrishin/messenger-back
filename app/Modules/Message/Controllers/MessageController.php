<?php
declare(strict_types=1);

namespace Modules\Message\Controllers;

use Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Message\Actions\CreateMessageAction;
use Modules\Message\Actions\GetChatMessagesAction;
use Modules\Message\Requests\CreateMessageRequest;
use Modules\Message\Requests\GetChatMessagesRequest;
use Modules\Message\Resources\MessageResource;

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
    public function getChatMessages(GetChatMessagesRequest $request): AnonymousResourceCollection
    {
        $messages = $this->action(GetChatMessagesAction::class)->run(
            $request->toDto()
        );
        return MessageResource::collection($messages);
    }
}
