<?php
declare(strict_types=1);

namespace App\Services\Message\Observers;

use App\Core\Parents\Observer;
use App\Services\Chat\Tasks\UpdateLastMessageIdTask;
use App\Services\File\Jobs\DeleteMessageFiles;
use App\Services\Message\Events\DeletedMessage;
use App\Services\Message\Models\Message;

final class MessageObserver extends Observer
{
    public function created(Message $message): void
    {
        $this->task(UpdateLastMessageIdTask::class)
            ->run($message->chat_id, $message->id);
    }

    public function deleted(Message $message): void
    {
        DeletedMessage::dispatch($message->id, $message->chat_id);
        DeleteMessageFiles::dispatch($message->id);
    }
}
