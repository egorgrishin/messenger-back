<?php
declare(strict_types=1);

namespace Modules\User\Requests;

use Core\Parents\Request;
use Modules\User\Dto\CreateUserDto;
use Modules\User\Models\User;

final class CreateUserRequest extends Request
{
    public function authorize(): bool
    {
        return !$this->user();
    }

    public function rules(): array
    {
        $user_class = User::class;
        return [
            'nick'     => "required|unique:$user_class|string|min:3|max:32",
            'password' => 'required|confirmed|string',
        ];
    }

    public function toDto(): CreateUserDto
    {
        return CreateUserDto::fromRequest($this);
    }
}
