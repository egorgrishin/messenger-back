<?php
declare(strict_types=1);

namespace Modules\Message\Requests;

use Core\Parents\Request;
use Modules\Message\Dto\GetChatMessagesDto;

final class GetChatMessagesRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        return [
            'startMessageId' => 'nullable|integer',
        ];
    }

    public function toDto(): GetChatMessagesDto
    {
        return GetChatMessagesDto::fromRequest($this);
    }
}
