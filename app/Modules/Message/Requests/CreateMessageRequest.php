<?php
declare(strict_types=1);

namespace Modules\Message\Requests;

use Core\Parents\Request;
use Modules\Message\Dto\CreateMessageDto;
use Modules\User\Models\User;

final class CreateMessageRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        $userClass = User::class;
        return [
            'toId' => "required|integer|exists:$userClass,id",
            'text' => 'required|string',
        ];
    }

    public function toDto(): CreateMessageDto
    {
        return CreateMessageDto::fromRequest($this);
    }
}
