<?php

namespace App\Services\Message\Events;

use App\Core\Parents\Event;
use App\Services\Chat\Events\ChatUpdated;
use App\Services\Chat\Models\Chat;
use App\Services\Message\Models\Message;
use App\Services\User\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageCreated extends Event implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    public function __construct(
        private readonly Message $message
    ) {}

    public function handle(): void
    {
        $chat = $this->getMessageChat();
        $chat->users->each(fn (User $user) => ChatUpdated::dispatch($chat, $user->id));
    }

    private function getMessageChat(): Chat
    {
        /** @var Chat $chat */
        $chat = $this->message
            ->chat()
            ->with('users:id')
            ->first();
        return $chat->setRelation('lastMessage', $this->message);
    }
}
