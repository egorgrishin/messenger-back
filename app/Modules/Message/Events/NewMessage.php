<?php
declare(strict_types=1);

namespace Modules\Message\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Message\Models\Message;
use Modules\Message\Resources\MessageResource;

final class NewMessage implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;

    public function __construct(
        private readonly Message $message,
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chats.' . $this->message['chat_id']);
    }

    public function broadcastWith(): array
    {
        return (new MessageResource($this->message))->toArray();
    }

    public function broadcastAs(): string
    {
        return 'message.new';
    }
}