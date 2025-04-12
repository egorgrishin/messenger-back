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
            'nick'     => 'required|string|min:3|max:32',
            'email'    => 'required|max:255|email|unique:users',
            'code'     => 'required|string',
            'password' => 'required|confirmed:passwordConfirmation|string',
        ];
    }

    public function toDto(): CreateUserDto
    {
        return CreateUserDto::fromRequest($this);
    }
}
