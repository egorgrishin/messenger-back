<?php

namespace App\Core\Classes\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Exceptions\HttpResponseException;

class Jwt
{
    private ?bool $authorized = null;
    private JwtHeader $header;
    private JwtPayload $payload;
    private JwtSignature $signature;

    public function __construct()
    {
        $this->header = new JwtHeader();
        $this->payload = new JwtPayload();
        $this->signature = new JwtSignature();
    }

    /**
     * Создает токен по объекту Authenticatable
     */
    public function createToken(Authenticatable $user): string
    {
        $this->header->setDecodedHeader(['algo' => 'sha256', 'type' => 'JWT']);
        $this->payload->setDecodedPayload([
            'id'   => $user->getAuthIdentifier(),
            'nick' => $user->nick,
            'exp'  => time() + env('ACCESS_TOKEN_LIFETIME', 900),
        ]);

        $key = $this->getKey();
        return $this->signature->create($this->header, $this->payload, $key);
    }

    public function setToken(string $token): self
    {
        $token = $this->getTokenAsArray($token);
        if (!$token || count($token) !== 3) {
            $this->throwException();
        }

        $this->header->setHeader($token[0]);
        $this->payload->setPayload($token[1]);
        $this->signature->setSignature($token[2]);
        return $this;
    }

    public function validate(): bool
    {
        if (!$this->header->validated() || !$this->payload->validated()) {
            return false;
        }
        $key = $this->getKey();

        return $this->authorized = $this->signature->check($this->header, $this->payload, $key);
    }

    public function getPayload(): array
    {
        if ($this->authorized === null && !$this->validate()) {
            $this->throwException();
        }
        return $this->payload->getDecodedPayload();
    }

    private function getTokenAsArray(string $token): array
    {
        return explode('.', $token);
    }

    private function getKey(): string
    {
        return env('JWT_KEY', '');
    }

    private function throwException()
    {
        throw new HttpResponseException(response()->json([
            'code' => 'FAILED_VALIDATION',
        ], 401));
    }
}
