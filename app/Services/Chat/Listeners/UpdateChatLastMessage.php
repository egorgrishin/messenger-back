<?php

namespace App\Services\Chat\Listeners;

use App\Services\Chat\Events\ChatUpdated;
use App\Services\Chat\Models\Chat;
use App\Services\Message\Events\MessageCreated;
use App\Services\Message\Events\MessageDeleted;
use App\Services\Message\Events\MessageUpdated;
use App\Services\Message\Models\Message;
use App\Services\User\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

final class UpdateChatLastMessage implements ShouldQueue
{
    public function handle(MessageCreated|MessageUpdated|MessageDeleted $event): void
    {
        $chat = $this->getMessageChat($event->message->chat_id);

        Log::debug($event->message);

        if ($event instanceof MessageCreated) {
            $chat->setRelation('lastMessage', $event->message);
        } elseif ($event instanceof MessageUpdated) {
            if ($chat->last_message_id === $event->message->id) {
                $chat->setRelation('lastMessage', $event->message);
            }
        } else {
            $prevMessage = $this->getPrevMessage($chat->id, $event->message->id);
            $chat->last_message_id = $prevMessage?->id;
            $chat->save();
            $chat->setRelation('lastMessage', $prevMessage);
        }

        $chat->users->each(fn (User $user) => ChatUpdated::dispatch($user->id, $chat));
    }

    private function getMessageChat(int $chatId): Chat
    {
        /** @var Chat */
        return Chat::query()
            ->where('id', $chatId)
            ->with('users:id')
            ->first();
    }

    private function getPrevMessage(int $chatId, int $messageId): ?Message
    {
        /** @var ?Message */
        return Message::query()
            ->select('id')
            ->where('chat_id', $chatId)
            ->where('id', '<>', $messageId)
            ->orderByDesc('id')
            ->first();
    }
}
