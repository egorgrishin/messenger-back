<?php
declare(strict_types=1);

namespace App\Services\User\Requests;

use App\Core\Parents\Request;

final class GetPasswordResetMethodsRequest extends Request
{
    public function authorize(): bool
    {
        return !$this->hasUser();
    }
}
