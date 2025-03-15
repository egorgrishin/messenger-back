<?php

namespace App\Services\Message\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\Message\Tasks\FindMessageTask;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DeleteMessageAction extends Action
{
    /**
     * Удаляет сообщение
     */
    public function run(int $messageId): void
    {
        $message = $this->task(FindMessageTask::class)->run($messageId);
        $message->canUpdate();

        try {
            $message->delete();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500, 'Не получилось удалить сообщение');
        }
    }
}