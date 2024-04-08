<?php
declare(strict_types=1);

namespace Modules\Chat\Requests;

use Core\Parents\Request;
use Modules\Chat\Dto\FindChatDto;

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
