<?php
declare(strict_types=1);

namespace App\Services\Message\Dto;

use App\Core\Parents\Dto;
use App\Services\Message\Requests\UpdateMessageRequest;

final readonly class UpdateMessageDto extends Dto
{
    public int     $messageId;
    public ?string $text;
    public array   $fileUuids;

    public static function fromRequest(UpdateMessageRequest $request): self
    {
        $dto = new self();
        $dto->messageId = (int) $request->route('messageId');
        $dto->text = $request->validated('text');
        $dto->fileUuids = $request->validated('fileUuids', []) ?: [];
        return $dto;
    }
}
