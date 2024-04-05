<?php
declare(strict_types=1);

namespace Modules\Chat\Dto;

use Core\Parents\Dto;
use Modules\Chat\Requests\GetUserChatsRequest;

final readonly class GetUserChatsDto extends Dto
{
    public int $userId;
    public ?int $startMessageId;

    public static function fromRequest(GetUserChatsRequest $request): self
    {
        $startMessageId = $request->input('startMessageId');

        $dto = new self();
        $dto->userId = (int) $request->route('userId');
        $dto->startMessageId = $startMessageId ? (int) $startMessageId : null;
        return $dto;
    }
}
