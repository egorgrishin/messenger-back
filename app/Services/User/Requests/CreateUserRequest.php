<?php
declare(strict_types=1);

namespace App\Services\User\Requests;

use App\Core\Parents\Request;
use App\Services\User\Dto\CreateUserDto;
use App\Services\User\Models\User;

final class CreateUserRequest extends Request
{
    public function rules(): array
    {
        $user_class = User::class;
        return [
            'nick'     => "required|unique:$user_class|string|min:3|max:32",
            'password' => 'required|confirmed|string',
            'avatar'   => 'nullable|image',
        ];
    }

    public function toDto(): CreateUserDto
    {
        return CreateUserDto::fromRequest($this);
    }
}
