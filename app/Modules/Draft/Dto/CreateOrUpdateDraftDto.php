<?php
declare(strict_types=1);

namespace Modules\Draft\Dto;

use Modules\Draft\Requests\CreateOrUpdateDraftRequest;
use Core\Parents\Dto;

final readonly class CreateOrUpdateDraftDto extends Dto
{
    public int $chatId;
    public int $userId;
    public ?string $text;

    public static function fromRequest(CreateOrUpdateDraftRequest $request): self
    {
        $dto = new self();
        $dto->chatId = $request->input('chatId');
        $dto->userId = $request->input('userId');
        $dto->text = $request->input('text');
        return $dto;
    }
}
