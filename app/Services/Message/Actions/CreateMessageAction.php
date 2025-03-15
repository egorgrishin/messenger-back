<?php
declare(strict_types=1);

namespace App\Services\Message\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\Chat\Tasks\UpdateLastMessageIdTask;
use App\Services\Chat\Tasks\UserInChatTask;
use App\Services\File\Tasks\AttachFilesToMessageTask;
use App\Services\Message\Dto\CreateMessageDto;
use App\Services\Message\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CreateMessageAction extends Action
{
    /**
     * Создает новое сообщение
     */
    public function run(CreateMessageDto $dto): Message
    {
        if (!$this->task(UserInChatTask::class)->run($dto->chatId, $dto->userId)) {
            throw new HttpException(403, 'Вы не состоите в чате');
        }

        try {
            return $this->createMessage($dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Добавляет сообщение в базу данных
     * @throws Throwable
     */
    private function createMessage(CreateMessageDto $dto): Message
    {
        return DB::transaction(function () use ($dto) {
            $message = new Message();
            $message->chat_id = $dto->chatId;
            $message->user_id = $dto->userId;
            $message->text = $dto->text;
            $message->save();

            $this->task(AttachFilesToMessageTask::class)->run($message->id, $dto->fileUuids);
            $this->task(UpdateLastMessageIdTask::class)->run($message->chat_id, $message->id);

            return $message->load('files');
        });
    }
}
