<?php
declare(strict_types=1);

namespace Modules\Auth\Dto;

use Core\Parents\Dto;
use Modules\Auth\Requests\LoginRequest;

final readonly class LoginDto extends Dto
{
    public string $nick;
    public string $password;
    public ?string $ipAddress;
    public ?string $userAgent;

    public static function fromRequest(LoginRequest $request): self
    {
        $dto = new self();
        $dto->nick = $request->validated('nick');
        $dto->password = $request->validated('password');
        $dto->ipAddress = $request->ip();
        $dto->userAgent = $request->userAgent();
        return $dto;
    }
}
