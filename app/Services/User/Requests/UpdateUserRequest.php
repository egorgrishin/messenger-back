<?php
declare(strict_types=1);

namespace App\Services\User\Requests;

use App\Core\Parents\Request;
use App\Services\User\Dto\UpdateUserDto;

final class UpdateUserRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()?->getAuthIdentifier() === (int) $this->route('userId');
    }

    public function rules(): array
    {
        return [
            'nick'      => 'required|string|min:3|max:32',
            'status'    => 'nullable|string|max:500',
            'shortLink' => 'nullable|string|min:3|max:32',
            'email'     => 'required_without:codeWord|email|max:255',
            'codeWord'  => 'required_without:email|string|max:255',
            'codeHint'  => 'nullable|string|max:255',
        ];
    }

    public function toDto(): UpdateUserDto
    {
        return UpdateUserDto::fromRequest($this);
    }
}
