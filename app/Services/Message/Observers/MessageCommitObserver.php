<?php
declare(strict_types=1);

namespace App\Services\Message\Observers;

use App\Core\Parents\Observer;
use App\Services\File\Jobs\DeleteMessageFiles;
use App\Services\Message\Events\Broadcast\MessageCreated as MessageCreatedBroadcast;
use App\Services\Message\Events\Broadcast\MessageDeleted as MessageDeletedBroadcast;
use App\Services\Message\Events\Broadcast\MessageUpdated as MessageUpdatedBroadcast;
use App\Services\Message\Events\MessageCreated;
use App\Services\Message\Events\MessageUpdated;
use App\Services\Message\Models\Message;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

final class MessageCommitObserver extends Observer implements ShouldHandleEventsAfterCommit
{
    public function created(Message $message): void
    {
        MessageCreated::dispatch($message);
        MessageCreatedBroadcast::dispatch($message);
    }

    public function updated(Message $message): void
    {
        MessageUpdated::dispatch($message);
        MessageUpdatedBroadcast::dispatch($message);
    }

    public function deleted(Message $message): void
    {
        MessageDeletedBroadcast::dispatch($message->id, $message->chat_id);
        DeleteMessageFiles::dispatch($message->id);
    }
}
