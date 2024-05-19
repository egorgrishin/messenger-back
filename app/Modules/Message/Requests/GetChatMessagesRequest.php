<?php
declare(strict_types=1);

namespace App\Modules\Message\Requests;

use App\Core\Parents\Request;
use Illuminate\Support\Facades\Log;
use App\Modules\Message\Dto\GetChatMessagesDto;

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
