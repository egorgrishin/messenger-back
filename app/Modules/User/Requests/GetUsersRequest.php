<?php
declare(strict_types=1);

namespace Modules\User\Requests;

use Core\Parents\Request;
use Modules\User\Dto\GetUsersDto;

final class GetUsersRequest extends Request
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'nick' => "required|min:3|max:32",
        ];
    }

    public function toDto(): GetUsersDto
    {
        return GetUsersDto::fromRequest($this);
    }
}
