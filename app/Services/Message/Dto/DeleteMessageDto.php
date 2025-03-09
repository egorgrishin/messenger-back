<?php

namespace App\Services\Message\Dto;

use App\Core\Parents\Dto;
use App\Services\Message\Requests\DeleteMessageRequest;

final readonly class DeleteMessageDto extends Dto
{
    public int $userId;
    public int $messageId;

    public static function fromRequest(DeleteMessageRequest $request): self
    {
        $dto = new self();
        $dto->userId = (int) $request->userId();
        $dto->messageId = (int) $request->route('messageId');
        return $dto;
    }
}