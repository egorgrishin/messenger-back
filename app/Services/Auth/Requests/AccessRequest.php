<?php
declare(strict_types=1);

namespace App\Services\Auth\Requests;

use App\Core\Parents\Request;
use App\Services\Auth\Dto\AccessDto;

final class AccessRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string',
        ];
    }

    public function toDto(): AccessDto
    {
        return AccessDto::fromRequest($this);
    }
}
