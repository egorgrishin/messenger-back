<?php
declare(strict_types=1);

namespace App\Modules\Chat\Dto;

use App\Core\Parents\Dto;
use App\Modules\Chat\Requests\GetUserChatsRequest;

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
