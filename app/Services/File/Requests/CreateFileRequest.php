<?php
declare(strict_types=1);

namespace App\Services\File\Requests;

use App\Core\Parents\Request;
use App\Services\File\Dto\CreateFileDto;

class CreateFileRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()?->getAuthIdentifier() === (int) $this->input('userId');
    }

    public function rules(): array
    {
        return [
            'userId' => 'required|integer',
            'file'   => 'required|file|mimes:bmp,gif,jpg,png,webp',
        ];
    }

    public function toDto(): CreateFileDto
    {
        return CreateFileDto::fromRequest($this);
    }
}
