<?php
declare(strict_types=1);

namespace Modules\Message\Observers;

use Core\Parents\Observer;
use Modules\Chat\Tasks\UpdateLastMessageIdTask;
use Modules\Message\Models\Message;

final class MessageObserver extends Observer
{
    public function created(Message $message): void
    {
        $this->task(UpdateLastMessageIdTask::class)
            ->run($message->chat_id, $message->id);
    }
}
