<?php
declare(strict_types=1);

namespace App\Services\User\Resources;

use App\Core\Parents\JsonResource;
use App\Services\User\Models\User;
use Illuminate\Http\Request;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;
        return [
            'id'        => $user->id,
            'nick'      => $user->nick,
            'avatarUrl' => $this->whenAppended('avatar_url'),
        ];
    }
}
