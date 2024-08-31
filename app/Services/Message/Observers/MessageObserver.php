<?php
declare(strict_types=1);

namespace App\Services\Message\Observers;

use App\Core\Parents\Observer;
use App\Services\Chat\Tasks\UpdateLastMessageIdTask;
use App\Services\Message\Models\Message;

final class MessageObserver extends Observer
{
    public function created(Message $message): void
    {
        $this->task(UpdateLastMessageIdTask::class)
            ->run($message->chat_id, $message->id);
    }
}
