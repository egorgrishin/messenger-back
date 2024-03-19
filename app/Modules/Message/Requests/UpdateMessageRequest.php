<?php
declare(strict_types=1);

namespace Modules\Message\Requests;

use Core\Parents\Request;
use Modules\Message\Dto\UpdateMessageDto;

final class UpdateMessageRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        return [
            'text' => 'required|string',
        ];
    }

    public function toDto(): UpdateMessageDto
    {
        return UpdateMessageDto::fromRequest($this);
    }
}
