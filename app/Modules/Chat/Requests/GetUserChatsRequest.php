<?php
declare(strict_types=1);

namespace Modules\Chat\Requests;

use Core\Parents\Request;
use Modules\Chat\Dto\GetUserChatsDto;

final class GetUserChatsRequest extends Request
{
    public function authorization(): bool
    {
        return $this->user()?->getAuthIdentifier() == $this->route('userId');
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
