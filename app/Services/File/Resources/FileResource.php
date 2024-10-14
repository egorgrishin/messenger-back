<?php
declare(strict_types=1);

namespace App\Services\File\Resources;

use App\Core\Parents\JsonResource;
use App\Services\File\Models\File;
use Illuminate\Http\Request;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var File $user */
        $file = $this->resource;

        return [
            'uuid' => $file->uuid,
        ];
    }
}
