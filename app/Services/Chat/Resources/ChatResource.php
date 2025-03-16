<?php
declare(strict_types=1);

namespace App\Services\Chat\Resources;

use App\Core\Parents\JsonResource;
use App\Services\Message\Resources\MessageResource;
use App\Services\User\Resources\UserResource;
use Illuminate\Http\Request;

final class ChatResource extends JsonResource
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
            'id'            => $chat->id,
            'lastMessageId' => $this->whenHas('last_message_id'),
            'users'         => UserResource::collection($this->whenLoaded('users')),
            'lastMessage'   => new MessageResource($this->whenLoaded('lastMessage')),
        ];
    }
}
