<?php
declare(strict_types=1);

namespace App\Services\Auth\Requests;

use App\Core\Parents\Request;
use App\Services\Auth\Dto\RefreshDto;

final class RefreshRequest extends Request
{
    public function rules(): array
    {
        return [
            'refreshToken' => 'required|string',
        ];
    }

    public function toDto(): RefreshDto
    {
        return RefreshDto::fromRequest($this);
    }
}
