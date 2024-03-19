<?php
declare(strict_types=1);

namespace Modules\Draft\Dto;

use Modules\Draft\Requests\CreateOrUpdateDraftRequest;
use Core\Parents\Dto;

final readonly class CreateOrUpdateDraftDto extends Dto
{
    public int $fromId;
    public int $toId;
    public ?string $text;

    public static function fromRequest(CreateOrUpdateDraftRequest $request): self
    {
        $dto = new self();
        $dto->fromId = $request->input('fromId');
        $dto->toId = $request->input('toId');
        $dto->text = $request->input('text');
        return $dto;
    }
}
