<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Core\Parents\Dto;
use App\Services\User\Requests\UpdateUserRequest;

final readonly class UpdateUserDto extends Dto
{
    public int $id;
    public string $nick;
    public ?string $status;
    public ?string $shortLink;
    public ?string $email;

    public static function fromRequest(UpdateUserRequest $request): static
    {
        $dto = new self();
        $dto->id = (int) $request->route('userId');
        $dto->nick = $request->validated('nick');
        $dto->status = $request->validated('status');
        $dto->shortLink = $request->validated('shortLink');
        $dto->email = $request->validated('email');
        return $dto;
    }
}
