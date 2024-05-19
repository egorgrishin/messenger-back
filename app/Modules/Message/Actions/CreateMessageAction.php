<?php
declare(strict_types=1);

namespace Modules\Message\Actions;

use Core\Exceptions\HttpException;
use Core\Parents\Action;
use Illuminate\Support\Facades\Log;
use Modules\Chat\Events\ChatUpdated;
use Modules\Chat\Models\Chat;
use Modules\Chat\Tasks\UserInChatTask;
use Modules\Message\Dto\CreateMessageDto;
use Modules\Message\Events\NewMessage;
use Modules\Message\Models\Message;
use Modules\User\Models\User;
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
            NewMessage::dispatch($message);
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
        $chat->setRelation('lastMessage', $message);
        $chat->users()
            ->select('users.id')
            ->get()
            ->each(fn (User $user) => ChatUpdated::dispatch($chat, $user->id));
    }
}
