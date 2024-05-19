<?php
declare(strict_types=1);

namespace App\Modules\Message\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use App\Modules\Message\Resources\MessageResource;

final class NewMessage implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(
        private readonly array $message,
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chats.' . $this->message['chat_id']);
    }

    /** @noinspection PhpUnused */
    public function broadcastWith(): array
    {
        return (new MessageResource($this->message))->resolve();
    }

    /** @noinspection PhpUnused */
    public function broadcastAs(): string
    {
        return 'message.new';
    }
}