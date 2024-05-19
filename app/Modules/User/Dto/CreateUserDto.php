<?php
declare(strict_types=1);

namespace App\Modules\User\Dto;

use App\Core\Parents\Dto;
use App\Modules\User\Requests\CreateUserRequest;

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
