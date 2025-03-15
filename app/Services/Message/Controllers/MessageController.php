<?php
declare(strict_types=1);

namespace App\Services\Message\Controllers;

use App\Core\Parents\Controller;
use App\Services\Message\Actions\DeleteMessageAction;
use App\Services\Message\Actions\UpdateMessageAction;
use App\Services\Message\Requests\UpdateMessageRequest;
use Illuminate\Http\JsonResponse;
use App\Services\Message\Actions\CreateMessageAction;
use App\Services\Message\Actions\GetChatMessagesAction;
use App\Services\Message\Requests\CreateMessageRequest;
use App\Services\Message\Requests\GetChatMessagesRequest;
use App\Services\Message\Resources\MessageResource;
use Illuminate\Http\Request;

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
     * Обновляет сообщение
     */
    public function update(UpdateMessageRequest $request): JsonResponse
    {
        $message = $this->action(UpdateMessageAction::class)->run(
            $request->toDto()
        );

        return $this
            ->resource($message, MessageResource::class)
            ->response()
            ->setStatusCode(204);
    }

    /**
     * Удаляет сообщение
     */
    public function delete(Request $request)
    {
        $this->action(DeleteMessageAction::class)->run(
            (int) $request->route('messageId')
        );

        return response()->json([], 204);
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
