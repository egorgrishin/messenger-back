<?php
declare(strict_types=1);

namespace App\Modules\Auth\Requests;

use App\Core\Parents\Request;
use App\Modules\Auth\Dto\RefreshDto;

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
