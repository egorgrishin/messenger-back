<?php
declare(strict_types=1);

namespace Modules\Message\Resources;

use Core\Parents\JsonResource;
use Illuminate\Http\Request;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array
    {
        $message = $this->resource;
        return [
            'id'        => $message['id'],
            'chatId'    => $message['chat_id'],
            'userId'    => $message['user_id'],
            'text'      => $message['text'],
            'createdAt' => $message['created_at'],
        ];
    }
}
