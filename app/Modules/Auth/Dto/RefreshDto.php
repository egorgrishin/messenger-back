<?php
declare(strict_types=1);

namespace Modules\Auth\Dto;

use Core\Parents\Dto;
use Modules\Auth\Requests\RefreshRequest;

final readonly class RefreshDto extends Dto
{
    public string $ulid;
    public ?string $ipAddress;
    public ?string $userAgent;

    public static function fromRequest(RefreshRequest $request): self
    {
        $dto = new self();
        $dto->ulid = $request->validated('refreshToken');
        $dto->ipAddress = $request->ip();
        $dto->userAgent = $request->userAgent();
        return $dto;
    }
}
