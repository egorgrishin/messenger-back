<?php
declare(strict_types=1);

namespace App\Services\User\Requests;

use App\Core\Parents\Request;
use App\Services\User\Dto\UpdateUserAvatarDto;

final class UpdateUserAvatarRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()?->getAuthIdentifier() === (int) $this->route('userId');
    }

    public function rules(): array
    {
        return [
            'avatar' => 'required|image',
        ];
    }
}