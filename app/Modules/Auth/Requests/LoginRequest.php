<?php
declare(strict_types=1);

namespace App\Modules\Auth\Requests;

use App\Core\Parents\Request;
use App\Modules\Auth\Dto\LoginDto;

final class LoginRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nick'     => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function toDto(): LoginDto
    {
        return LoginDto::fromRequest($this);
    }
}
