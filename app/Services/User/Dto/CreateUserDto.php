<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Core\Parents\Dto;
use App\Services\User\Requests\CreateUserRequest;
use Illuminate\Http\UploadedFile;

final readonly class CreateUserDto extends Dto
{
    public string $login;
    public string $nick;
    public string $password;
    public ?string $shortLink;
    public ?string $email;
    public ?string $codeWord;
    public ?string $codeHint;
    public ?UploadedFile $avatar;

    public static function fromRequest(CreateUserRequest $request): self
    {
        $dto = new self();
        $dto->login = $request->validated('login');
        $dto->nick = $request->validated('nick');
        $dto->password = $request->validated('password');
        $dto->shortLink = $request->validated('shortLink');
        $dto->email = $request->validated('email');
        $dto->codeWord = $request->validated('codeWord');
        $dto->codeHint = $request->validated('codeHint');
        $dto->avatar = $request->validated('avatar');
        return $dto;
    }
}
