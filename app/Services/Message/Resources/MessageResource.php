<?php
declare(strict_types=1);

namespace App\Services\Message\Resources;

use App\Core\Parents\JsonResource;
use App\Services\File\Resources\FileResource;
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
            'id'         => $message->id,
            'chatId'     => $message->chat_id,
            'userId'     => $message->user_id,
            'text'       => $message->text,
            'files'      => FileResource::collection($this->whenLoaded('files')),
            'filesCount' => $this->whenCounted('files'),
            'createdAt'  => $message->created_at,
        ];
    }
}
