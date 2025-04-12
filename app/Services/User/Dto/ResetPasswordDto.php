<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Core\Parents\Dto;
use App\Services\User\Requests\ResetPasswordRequest;

final readonly class ResetPasswordDto extends Dto
{
    public ?string $email;
    public string  $code;
    public string  $password;

    public static function fromRequest(ResetPasswordRequest $request): self
    {
        $dto = new self();
        $dto->email = $request->hasUser() ? null : $request->validated('email');
        $dto->code = $request->validated('code');
        $dto->password = $request->validated('password');
        return $dto;
    }
}
