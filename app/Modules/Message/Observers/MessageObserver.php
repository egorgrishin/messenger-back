<?php
declare(strict_types=1);

namespace Modules\Message\Observers;

use Core\Parents\Observer;
use Modules\Draft\Tasks\ClearDraftTask;
use Modules\Message\Models\Message;

final class MessageObserver extends Observer
{
    public function created(Message $message): void
    {
        $this->task(ClearDraftTask::class)->run($message->from_id, $message->to_id);
    }
}
