<?php
declare(strict_types=1);

namespace App\Services\User\Requests;

use App\Core\Parents\Request;

final class UpdateUserAvatarRequest extends Request
{
    public function authorize(): bool
    {
        return $this->userId() === $this->routeUserId();
    }

    public function rules(): array
    {
        return [
            'avatar' => 'required|image',
        ];
    }
}