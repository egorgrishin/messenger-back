<?php

namespace App\Core\Parents;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @method Authenticatable|null user($guard = null)
 */
abstract class Request extends FormRequest
{
    public function hasUser(): bool
    {
        return (bool) $this->user();
    }
}
