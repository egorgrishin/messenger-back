<?php
declare(strict_types=1);

namespace Modules\Chat\Requests;

use Core\Parents\Request;
use Illuminate\Validation\Rule;
use Modules\Chat\Dto\CreateChatDto;
use Modules\Chat\Rules\ValueInArray;

final class CreateChatRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        return [
            'title'    => [
                Rule::requiredIf(fn (): bool => !$this->input('isDialog')),
                'max:127',
                'string',
            ],
            'isDialog' => 'required|boolean',
            'users'    => [
                Rule::requiredIf(fn (): bool => $this->input('isDialog')),
                'array',
                new ValueInArray($this->user()->getAuthIdentifier()),
            ],
            'users.*'  => 'required|distinct|integer',
        ];
    }

    public function toDto(): CreateChatDto
    {
        return CreateChatDto::fromRequest($this);
    }
}
