<?php
declare(strict_types=1);

namespace App\Services\Message\Requests;

use App\Core\Parents\Request;
use App\Services\Message\Dto\UpdateMessageDto;
use Illuminate\Validation\Rule;

final class UpdateMessageRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        return [
            'text'        => [
                Rule::requiredIf(fn (): bool => !$this->input('fileUuids')),
                'nullable',
                'string',
            ],
            'fileUuids'   => 'nullable|array',
            'fileUuids.*' => 'string',
        ];
    }

    public function toDto(): UpdateMessageDto
    {
        return UpdateMessageDto::fromRequest($this);
    }
}
