<?php
declare(strict_types=1);

namespace Modules\Message\Dto;

use Core\Parents\Dto;
use Modules\Message\Requests\GetChatMessagesRequest;

final readonly class GetChatMessagesDto extends Dto
{
    public int $chatId;
    public ?int $startMessageId;

    public static function fromRequest(GetChatMessagesRequest $request): self
    {
        $startMessageId = $request->input('startMessageId');

        $dto = new self();
        $dto->chatId = (int) $request->route('chatId');
        $dto->startMessageId = $startMessageId ? (int) $startMessageId : null;
        return $dto;
    }
}
