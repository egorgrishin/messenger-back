<?php
declare(strict_types=1);

namespace App\Services\Chat\Requests;

use App\Core\Parents\Request;
use App\Services\Chat\Dto\CreateChatDto;

final class CreateChatRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        return [
            'users'   => 'required|array|size:2',
            'users.*' => 'required|distinct|integer',
        ];
    }

    public function toDto(): CreateChatDto
    {
        return CreateChatDto::fromRequest($this);
    }
}
