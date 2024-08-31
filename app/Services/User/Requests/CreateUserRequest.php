<?php
declare(strict_types=1);

namespace App\Services\User\Requests;

use App\Core\Parents\Request;
use App\Services\User\Dto\CreateUserDto;

final class CreateUserRequest extends Request
{
    public function rules(): array
    {
        return [
            'login'     => 'required|string|min:3|max:32',
            'nick'      => 'required|string|min:3|max:32',
            'password'  => 'required|confirmed|string',
            'shortLink' => 'nullable|string|min:3|max:32',
            'email'     => 'required_without:codeWord|email|max:255',
            'codeWord'  => 'required_without:email|string|max:255',
            'codeHint'  => 'nullable|string|max:255',
            'avatar'    => 'nullable|image',
        ];
    }

    public function toDto(): CreateUserDto
    {
        return CreateUserDto::fromRequest($this);
    }
}
