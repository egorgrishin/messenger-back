<?php
declare(strict_types=1);

namespace App\Services\User\Resources;

use App\Core\Parents\JsonResource;
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
        $user = $this->resource;
        return [
            'id'         => $user['id'],
            'nick'       => $user['nick'],
            'createdAt' => $this->when(
                array_key_exists('created_at', $user),
                fn () => $user['created_at'],
            ),
            'updatedAt' => $this->when(
                array_key_exists('updated_at', $user),
                fn () => $user['updated_at'],
            ),
        ];
    }
}
