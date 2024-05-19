<?php
declare(strict_types=1);

namespace Modules\Chat\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Chat\Models\Chat;
use Modules\Chat\Resources\ChatResource;

class ChatUpdated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(
        private readonly Chat $chat,
        private readonly int $userId,
    ) {
        if (!$this->chat->relationLoaded('users')) {
            $chat->load('users:id,nick');
        }
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("users.$this->userId.chats");
    }

    public function broadcastWith(): array
    {
        return (new ChatResource($this->chat->toArray()))->resolve();
    }

    public function broadcastAs(): string
    {
        return 'chat.updated';
    }
}