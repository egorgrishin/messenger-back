<?php
declare(strict_types=1);

namespace App\Services\Chat\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Services\Chat\Models\Chat;
use App\Services\Chat\Resources\ChatResource;

class ChatUpdated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;

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

    /** @noinspection PhpUnused */
    public function broadcastWith(): array
    {
        return (new ChatResource($this->chat->toArray()))->resolve();
    }

    /** @noinspection PhpUnused */
    public function broadcastAs(): string
    {
        return 'chat.updated';
    }
}