<?php
declare(strict_types=1);

namespace Modules\Auth\Requests;

use Core\Parents\Request;
use Modules\Auth\Dto\LoginDto;

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
