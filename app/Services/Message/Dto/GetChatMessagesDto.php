<?php
declare(strict_types=1);

namespace App\Services\Message\Dto;

use App\Core\Parents\Dto;
use App\Services\Message\Requests\GetChatMessagesRequest;

final readonly class GetChatMessagesDto extends Dto
{
    public int $chatId;
    public int $userId;
    public ?int $startId;

    public static function fromRequest(GetChatMessagesRequest $request): self
    {
        $startId = $request->input('startId');

        $dto = new self();
        $dto->chatId = (int) $request->route('chatId');
        $dto->userId = $request->userId();
        $dto->startId = $startId ? (int) $startId : null;
        return $dto;
    }
}
