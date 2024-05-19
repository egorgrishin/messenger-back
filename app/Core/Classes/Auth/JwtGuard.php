<?php

namespace App\Core\Classes\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JwtGuard implements StatefulGuard
{
    use GuardHelpers;

    private Request          $request;
    private ?Authenticatable $last_attempted;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user(): ?Authenticatable
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if (is_null($token = $this->request->bearerToken())) {
            return null;
        }

        return $this->user = $this->provider->retrieveById(
            (new Jwt())->setToken($token)->getPayload()
        );
    }

    /**
     * Validate a user's credentials.
     */
    public function validate(array $credentials = []): bool
    {
        $this->last_attempted = $user = $this->provider->retrieveByCredentials($credentials);
        return $this->hasValidCredentials($user, $credentials);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     */
    public function attempt(array $credentials = [], $remember = false): string|false
    {
        $this->last_attempted = $user = $this->provider->retrieveByCredentials($credentials);

        return $this->hasValidCredentials($user, $credentials)
            ? $this->login($user, $remember)
            : false;
    }

    /**
     * Log a user into the application without sessions or cookies.
     */
    public function once(array $credentials = []): bool
    {
        if ($this->validate($credentials)) {
            $this->setUser($this->last_attempted);
            return true;
        }

        return false;
    }

    /**
     * Log a user into the application.
     */
    public function login(Authenticatable $user, $remember = false): string
    {
        $this->setUser($user);
        return (new Jwt())->createToken($user);
    }

    /**
     * Log the given user ID into the application.
     */
    public function loginUsingId($id, $remember = false): ?string
    {
        $user = $this->provider->retrieveById($id);
        return $user !== null
            ? $this->login($user, $remember)
            : false;
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     */
    public function onceUsingId($id): bool
    {
        if (!is_null($user = $this->provider->retrieveById($id))) {
            $this->setUser($user);
            return true;
        }

        return false;
    }

    /**
     * Determine if the user was authenticated via “remember me” cookie.
     */
    public function viaRemember(): bool
    {
        return false;
    }

    /**
     * Log the user out of the application.
     */
    public function logout(): void
    {
        $this->user = null;
    }

    /**
     * Determine valid credentials.
     */
    private function hasValidCredentials(?Authenticatable $user, array $credentials): bool
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Set the current request instance.
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;
        return $this;
    }
}
