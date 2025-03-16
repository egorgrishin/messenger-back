<?php
declare(strict_types=1);

namespace App\Services\Chat\Dto;

use App\Core\Parents\Dto;
use App\Services\Chat\Requests\CreateChatRequest;

final readonly class CreateChatDto extends Dto
{
    public array $users;

    public static function fromRequest(CreateChatRequest $request): self
    {
        $dto = new self();
        $dto->users = $request->input('users');
        return $dto;
    }
}