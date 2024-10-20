<?php
declare(strict_types=1);

namespace App\Services\Chat\Requests;

use App\Core\Parents\Request;
use App\Services\Chat\Dto\GetUserChatsDto;

final class GetUserChatsRequest extends Request
{
    public function authorize(): bool
    {
        return $this->userId() === $this->routeUserId();
    }

    public function rules(): array
    {
        return [
            'startMessageId' => 'nullable|integer',
        ];
    }

    public function toDto(): GetUserChatsDto
    {
        return GetUserChatsDto::fromRequest($this);
    }
}
