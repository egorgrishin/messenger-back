<?php
declare(strict_types=1);

namespace Modules\Auth\Requests;

use Core\Parents\Request;
use Modules\Auth\Dto\RefreshDto;

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
