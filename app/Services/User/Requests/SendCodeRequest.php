<?php

namespace App\Services\User\Requests;

use App\Core\Parents\Request;

final class SendCodeRequest extends Request
{
    public function rules(): array
    {
        return $this->hasUser()
            ? []
            : ['email' => 'required|max:255|email'];
    }
}
