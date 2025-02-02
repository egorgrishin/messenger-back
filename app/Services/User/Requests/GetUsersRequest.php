<?php
declare(strict_types=1);

namespace App\Services\User\Requests;

use App\Core\Parents\Request;
use App\Services\User\Dto\GetUsersDto;

final class GetUsersRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function rules(): array
    {
        return [
            'nick'    => 'required|min:1|max:32',
            'startId' => 'nullable|integer',
        ];
    }

    public function toDto(): GetUsersDto
    {
        return GetUsersDto::fromRequest($this);
    }
}
