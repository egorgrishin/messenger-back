<?php
declare(strict_types=1);

namespace App\Services\User\Requests;

use App\Core\Parents\Request;
use App\Services\User\Dto\UpdateUserDto;

final class UpdateUserRequest extends Request
{
    public function authorize(): bool
    {
        return $this->userId() === $this->routeUserId();
    }

    public function rules(): array
    {
        return [
            'nick'      => 'required|string|min:3|max:32',
            'status'    => 'nullable|string|max:500',
            'shortLink' => 'nullable|string|min:3|max:32',
            'email'     => 'required|email|max:255',
        ];
    }

    public function toDto(): UpdateUserDto
    {
        return UpdateUserDto::fromRequest($this);
    }
}
