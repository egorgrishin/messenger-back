<?php

namespace App\Services\User\Requests;

use App\Core\Parents\Request;
use App\Services\User\Dto\ResetPasswordDto;

final class ResetPasswordRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'code'     => 'required|string',
            'password' => 'required|string|confirmed:passwordConfirmation',
        ];

        if (!$this->hasUser()) {
            $rules['email'] = 'required|max:255|email';
        }

        return $rules;
    }

    public function toDto(): ResetPasswordDto
    {
        return ResetPasswordDto::fromRequest($this);
    }
}
