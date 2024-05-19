<?php
declare(strict_types=1);

namespace Modules\Chat\Requests;

use Core\Parents\Request;
use Illuminate\Support\Facades\Log;
use Modules\Chat\Dto\GetUserChatsDto;

final class GetUserChatsRequest extends Request
{
    public function authorize(): bool
    {
        Log::debug('1');
        Log::debug($this->user() ?? 'null');
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
