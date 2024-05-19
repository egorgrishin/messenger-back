<?php
declare(strict_types=1);

namespace Modules\Chat\Dto;

use Core\Parents\Dto;
use Modules\Chat\Requests\CreateChatRequest;

final readonly class CreateChatDto extends Dto
{
    public ?string $title;
    public bool $isDialog;
    public ?array $users;

    public static function fromRequest(CreateChatRequest $request): self
    {
        $dto = new self();
        $dto->title = $request->input('title');
        $dto->isDialog = $request->input('isDialog');
        $dto->users = $request->input('users');
        return $dto;
    }
}