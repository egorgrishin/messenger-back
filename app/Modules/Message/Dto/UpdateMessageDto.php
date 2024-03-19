<?php
declare(strict_types=1);

namespace Modules\Message\Dto;

use Core\Parents\Dto;
use Modules\Message\Requests\UpdateMessageRequest;

final readonly class UpdateMessageDto extends Dto
{
    public int $id;
    public int $fromId;
    public string $text;

    public static function fromRequest(UpdateMessageRequest $request): self
    {
        $dto = new self();
        $dto->id = (int) $request->route('messageId');
        $dto->fromId = $request->user()->getAuthIdentifier();
        $dto->text = $request->input('text');
        return $dto;
    }
}
