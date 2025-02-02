<?php
declare(strict_types=1);

namespace App\Services\Auth\Dto;

use App\Core\Parents\Dto;
use App\Services\Auth\Requests\AccessRequest;

final readonly class AccessDto extends Dto
{
    public string $email;
    public string $password;
    public ?string $ipAddress;
    public ?string $userAgent;

    public static function fromRequest(AccessRequest $request): self
    {
        $dto = new self();
        $dto->email = $request->validated('email');
        $dto->password = $request->validated('password');
        $dto->ipAddress = $request->ip();
        $dto->userAgent = $request->userAgent();
        return $dto;
    }
}
