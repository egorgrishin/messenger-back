<?php
declare(strict_types=1);

namespace Modules\User\Dto;

use Core\Parents\Dto;
use Modules\User\Requests\GetUsersRequest;

final readonly class GetUsersDto extends Dto
{
    public int $user_id;
    public string $nick;

    public static function fromRequest(GetUsersRequest $request): self
    {
        $dto = new self();
        $dto->user_id = $request->user()?->getAuthIdentifier();
        $dto->nick = $request->validated('nick');
        return $dto;
    }
}
