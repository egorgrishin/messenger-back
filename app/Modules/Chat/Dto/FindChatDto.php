<?php
declare(strict_types=1);

namespace App\Modules\Chat\Dto;

use App\Core\Parents\Dto;
use App\Modules\Chat\Requests\FindChatRequest;

final readonly class FindChatDto extends Dto
{
    public int $chatId;
    public int $userId;

    public static function fromRequest(FindChatRequest $request): self
    {
        $dto = new self();
        $dto->chatId = (int) $request->route('chatId');
        $dto->userId = (int) $request->user()->getAuthIdentifier();
        return $dto;
    }
}
