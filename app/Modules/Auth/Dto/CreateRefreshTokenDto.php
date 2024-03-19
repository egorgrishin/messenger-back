<?php
declare(strict_types=1);

namespace Modules\Auth\Dto;

use Core\Parents\Dto;

final readonly class CreateRefreshTokenDto extends Dto
{
    public function __construct(
        public ?string $chain,
        public int     $userId,
        public string  $ipAddress,
        public ?string $userAgent,
    ) {}
}
