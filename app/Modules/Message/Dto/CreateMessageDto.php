<?php
declare(strict_types=1);

namespace App\Modules\Message\Dto;

use App\Core\Parents\Dto;
use App\Modules\Message\Requests\CreateMessageRequest;

final readonly class CreateMessageDto extends Dto
{
    public int $chatId;
    public int $userId;
    public string $text;

    public static function fromRequest(CreateMessageRequest $request): self
    {
        $dto = new self();
        $dto->chatId = $request->input('chatId');
        $dto->userId = $request->user()->getAuthIdentifier();
        $dto->text = $request->input('text');
        return $dto;
    }
}
