<?php
declare(strict_types=1);

namespace App\Services\Chat\Requests;

use App\Core\Parents\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Chat\Dto\GetUserChatsDto;

final class GetUserChatsRequest extends Request
{
    public function authorize(): bool
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
