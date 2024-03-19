<?php
declare(strict_types=1);

namespace Modules\Message\Actions;

use Core\Parents\Action;
use Illuminate\Support\Facades\Log;
use Modules\Message\Dto\UpdateMessageDto;
use Modules\Message\Models\Message;
use Modules\Message\Tasks\FindMessageTask;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class UpdateMessageAction extends Action
{
    /**
     * Обновляет сообщение
     */
    public function run(UpdateMessageDto $dto): void
    {
        $message = $this->task(FindMessageTask::class)->run($dto->id);
        $this->validate($message, $dto);

        try {
            $this->updateMessage($message, $dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Валидация данных
     */
    private function validate(?Message $message, UpdateMessageDto $dto): void
    {
        if (!$message) {
            throw new HttpException(404);
        }
        if ($message->from_id != $dto->fromId) {
            throw new HttpException(403);
        }
        if ($message->text == $dto->text) {
            throw new HttpException(422);
        }
    }

    /**
     * Обновляет сообщение в базе данных
     * @throws Throwable
     */
    private function updateMessage(Message $message, UpdateMessageDto $dto): void
    {
        $message->text = $dto->text;
        $message->saveOrFail();
    }
}
