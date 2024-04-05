<?php
declare(strict_types=1);

namespace Modules\Message\Actions;

use Core\Parents\Action;
use Illuminate\Support\Facades\Log;
use Modules\Message\Dto\CreateMessageDto;
use Modules\Message\Models\Message;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class CreateMessageAction extends Action
{
    public function run(CreateMessageDto $dto): void
    {
        try {
            $message = new Message();
            $message->chat_id = $dto->chatId;
            $message->user_id = $dto->userId;
            $message->text = $dto->text;
            $message->saveOrFail();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }
}
