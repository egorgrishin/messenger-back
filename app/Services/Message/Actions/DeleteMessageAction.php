<?php

namespace App\Services\Message\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\Message\Dto\DeleteMessageDto;
use App\Services\Message\Models\Message;
use App\Services\Message\Tasks\FindMessageTask;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DeleteMessageAction extends Action
{
    /**
     * Удаляет сообщение
     */
    public function run(DeleteMessageDto $dto): void
    {
        $message = $this->task(FindMessageTask::class)->run($dto->messageId);
        $this->validate($message, $dto->userId);

        try {
            $message->delete();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500, 'Не получилось удалить сообщение');
        }
    }

    /**
     * Проверяет возможность удаления сообщения
     */
    private function validate(Message $message, int $userId): void
    {
        if ($message->user_id !== $userId) {
            throw new HttpException(403, 'Вы не можете удалить это сообщение');
        }
    }
}