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

    /**
     * Возвращает ID пользователя
     */
    public function userId(): ?int
    {
        return $this->user()?->getAuthIdentifier();
    }

    /**
     * Возвращает ID пользователя
     */
    public function routeUserId(string $route = 'userId'): ?int
    {
        return (int) $this->route($route);
    }
}
