<?php
declare(strict_types=1);

namespace App\Services\Message\Dto;

use App\Core\Parents\Dto;
use App\Services\Message\Requests\CreateMessageRequest;

final readonly class CreateMessageDto extends Dto
{
    public int     $chatId;
    public int     $userId;
    public ?string $text;
    public array   $fileUuids;

    public static function fromRequest(CreateMessageRequest $request): self
    {
        $dto = new self();
        $dto->chatId = $request->validated('chatId');
        $dto->userId = $request->user()->getAuthIdentifier();
        $dto->text = $request->validated('text');
        $dto->fileUuids = $request->validated('fileUuids', []);
        return $dto;
    }
}
