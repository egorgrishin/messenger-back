<?php
declare(strict_types=1);

namespace Modules\Chat\Resources;

use Core\Parents\JsonResource;
use Illuminate\Http\Request;
use Modules\Message\Resources\MessageResource;
use Modules\User\Resources\UserResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $chat = $this->resource;
        return [
            'id'            => $chat['id'],
            'title'         => $chat['title'],
            'isDialog'      => $chat['is_dialog'],
            'lastMessageId' => $this->when(
                array_key_exists('last_message_id', $chat),
                fn () => $chat['last_message_id'],
            ),
            'users'         => $this->when(
                array_key_exists('users', $chat),
                fn () => UserResource::collection($chat['users']),
            ),
            'lastMessage'   => $this->when(
                array_key_exists('last_message', $chat),
                fn () => new MessageResource($chat['last_message']),
            ),
        ];
    }
}
