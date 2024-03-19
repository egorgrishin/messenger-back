<?php
declare(strict_types=1);

namespace Modules\Message\Controllers;

use Core\Parents\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Message\Actions\CreateMessageAction;
use Modules\Message\Actions\UpdateMessageAction;
use Modules\Message\Requests\CreateMessageRequest;
use Modules\Message\Requests\UpdateMessageRequest;

final class MessageController extends Controller
{
    /**
     * Создает сообщение
     */
    public function create(CreateMessageRequest $request): JsonResponse
    {
        $this->action(CreateMessageAction::class)->run(
            $request->toDto()
        );
        return $this->json([], 201);
    }

    /**
     * Обновляет сообщение
     */
    public function update(UpdateMessageRequest $request): JsonResponse
    {
        $this->action(UpdateMessageAction::class)->run(
            $request->toDto()
        );
        return $this->json([], 204);
    }
}
