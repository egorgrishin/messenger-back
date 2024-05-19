<?php
declare(strict_types=1);

namespace Modules\Message\Requests;

use Core\Parents\Request;
use Illuminate\Support\Facades\Log;
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
            'startId' => 'nullable|integer',
        ];
    }

    public function toDto(): GetChatMessagesDto
    {
        return GetChatMessagesDto::fromRequest($this);
    }
}
