<?php
declare(strict_types=1);

namespace App\Services\Message\Events\Broadcast;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;

final class MessageDeleted implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(
        private readonly int $messageId,
        private readonly int $chatId,
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chats.' . $this->chatId);
    }

    /** @noinspection PhpUnused */
    public function broadcastWith(): array
    {
        return [
            'messageId' => $this->messageId,
        ];
    }

    /** @noinspection PhpUnused */
    public function broadcastAs(): string
    {
        return 'message.deleted';
    }
}