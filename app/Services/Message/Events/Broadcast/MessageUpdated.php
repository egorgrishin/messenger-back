<?php
declare(strict_types=1);

namespace App\Services\Message\Events\Broadcast;

use App\Services\Message\Models\Message;
use App\Services\Message\Resources\MessageResource;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;

final class MessageUpdated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(
        private readonly Message $message,
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chats.' . $this->message->chat_id);
    }

    /** @noinspection PhpUnused */
    public function broadcastWith(): array
    {
        return (new MessageResource($this->message))->resolve();
    }

    /** @noinspection PhpUnused */
    public function broadcastAs(): string
    {
        return 'message.updated';
    }
}