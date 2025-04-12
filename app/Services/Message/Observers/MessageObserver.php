<?php
declare(strict_types=1);

namespace App\Services\Message\Observers;

use App\Core\Parents\Observer;
use App\Services\File\Jobs\DeleteFiles;
use App\Services\Message\Events\MessageDeleted;
use App\Services\Message\Models\Message;

final class MessageObserver extends Observer
{
    public function deleting(Message $message): void
    {
        MessageDeleted::dispatch($message);
        DeleteFiles::dispatch($message->files()->get());
    }
}
