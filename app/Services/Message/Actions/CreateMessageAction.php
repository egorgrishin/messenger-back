<?php
declare(strict_types=1);

namespace App\Services\Message\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Support\Facades\Log;
use App\Services\Chat\Events\ChatUpdated;
use App\Services\Chat\Models\Chat;
use App\Services\Chat\Tasks\UserInChatTask;
use App\Services\Message\Dto\CreateMessageDto;
use App\Services\Message\Events\NewMessage;
use App\Services\Message\Models\Message;
use App\Services\User\Models\User;
use Throwable;

final class CreateMessageAction extends Action
{
    public function run(CreateMessageDto $dto): array
    {
        if (!$this->task(UserInChatTask::class)->run($dto->chatId, $dto->userId)) {
            throw new HttpException(403, 'Вы не состоите в чате');
        }

        try {
            $message = $this->createMessage($dto);
            NewMessage::dispatch($message->toArray());
            $this->sendChatUpdatedEvent($message);
            return $message->toArray();
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
        $message = new Message();
        $message->chat_id = $dto->chatId;
        $message->user_id = $dto->userId;
        $message->text = $dto->text;
        $message->saveOrFail();
        return $message;
    }

    /**
     * Отправляет событие о том, что последнее сообщение в чате было изменено
     */
    private function sendChatUpdatedEvent(Message $message): void
    {
        /** @var Chat $chat */
        $chat = $message->chat()->first();
        $chat->load('users:id,nick');
        $chat->setRelation('lastMessage', $message);
        $chat->users
            ->each(fn (User $user) => ChatUpdated::dispatch($chat, $user->id));
    }
}
