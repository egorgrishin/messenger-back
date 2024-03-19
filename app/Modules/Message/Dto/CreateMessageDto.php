<?php
declare(strict_types=1);

namespace Modules\Message\Dto;

use Core\Parents\Dto;
use Modules\Message\Requests\CreateMessageRequest;

final readonly class CreateMessageDto extends Dto
{
    public int $fromId;
    public int $toId;
    public string $text;

    public static function fromRequest(CreateMessageRequest $request): self
    {
        $dto = new self();
        $dto->fromId = $request->user()->getAuthIdentifier();
        $dto->toId = $request->input('toId');
        $dto->text = $request->input('text');
        return $dto;
    }
}
