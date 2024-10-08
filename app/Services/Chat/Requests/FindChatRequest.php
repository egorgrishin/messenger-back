<?php
declare(strict_types=1);

namespace App\Services\Chat\Requests;

use App\Core\Parents\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Chat\Dto\FindChatDto;

class FindChatRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function toDto(): FindChatDto
    {
        return FindChatDto::fromRequest($this);
    }
}
