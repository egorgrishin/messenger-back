<?php
declare(strict_types=1);

namespace App\Modules\Chat\Requests;

use App\Core\Parents\Request;
use Illuminate\Validation\Rule;
use App\Modules\Chat\Dto\CreateChatDto;
use App\Modules\Chat\Rules\ValueInArray;

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
