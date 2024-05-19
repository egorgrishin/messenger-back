<?php
declare(strict_types=1);

namespace App\Modules\Message\Resources;

use App\Core\Parents\JsonResource;
use Illuminate\Http\Request;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
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
