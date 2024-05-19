<?php
declare(strict_types=1);

namespace App\Modules\Message\Requests;

use App\Core\Parents\Request;
use App\Modules\Chat\Models\Chat;
use App\Modules\Message\Dto\CreateMessageDto;

final class CreateMessageRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        $chatClass = Chat::class;
        return [
            'chatId' => "required|integer|exists:$chatClass,id",
            'text'   => 'required|string',
        ];
    }

    public function toDto(): CreateMessageDto
    {
        return CreateMessageDto::fromRequest($this);
    }
}
