<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Core\Parents\Dto;
use App\Services\User\Requests\CreateUserRequest;
use Illuminate\Http\UploadedFile;

final readonly class CreateUserDto extends Dto
{
    public string $nick;
    public string $password;
    public ?UploadedFile $avatar;

    public static function fromRequest(CreateUserRequest $request): self
    {
        $dto = new self();
        $dto->nick = $request->validated('nick');
        $dto->password = $request->validated('password');
        $dto->avatar = $request->validated('avatar');
        return $dto;
    }
}
