<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Core\Parents\Dto;
use App\Services\User\Requests\CreateUserRequest;

final readonly class CreateUserDto extends Dto
{
    public string $nick;
    public string $email;
    public string $code;
    public string $password;

    public static function fromRequest(CreateUserRequest $request): self
    {
        $dto = new self();
        $dto->nick = $request->validated('nick');
        $dto->email = $request->validated('email');
        $dto->code = $request->validated('code');
        $dto->password = $request->validated('password');
        return $dto;
    }
}
