<?php
declare(strict_types=1);

namespace App\Services\Chat\Requests;

use App\Core\Parents\Request;

final class CreateChatRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        return [
            'interlocutorId' => 'required|integer',
        ];
    }
}
