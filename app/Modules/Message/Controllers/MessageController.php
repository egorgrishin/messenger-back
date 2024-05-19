<?php
declare(strict_types=1);

namespace App\Modules\Message\Controllers;

use App\Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use App\Modules\Message\Actions\CreateMessageAction;
use App\Modules\Message\Actions\GetChatMessagesAction;
use App\Modules\Message\Requests\CreateMessageRequest;
use App\Modules\Message\Requests\GetChatMessagesRequest;
use App\Modules\Message\Resources\MessageResource;

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
