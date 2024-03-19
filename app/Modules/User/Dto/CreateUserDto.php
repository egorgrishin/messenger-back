<?php
declare(strict_types=1);

namespace Modules\User\Dto;

use Core\Parents\Dto;
use Modules\User\Requests\CreateUserRequest;

final readonly class CreateUserDto extends Dto
{
    public string $nick;
    public string $password;

    public static function fromRequest(CreateUserRequest $request): self
    {
        $dto = new self();
        $dto->nick = $request->validated('nick');
        $dto->password = $request->validated('password');
        return $dto;
    }
}
