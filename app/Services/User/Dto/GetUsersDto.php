<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Core\Parents\Dto;
use App\Services\User\Requests\GetUsersRequest;

final readonly class GetUsersDto extends Dto
{
    public int    $user_id;
    public string $nick;
    public ?int   $startId;

    public static function fromRequest(GetUsersRequest $request): self
    {
        $startId = $request->validated('startId');

        $dto = new self();
        $dto->user_id = $request->userId();
        $dto->nick = $request->validated('nick');
        $dto->startId = $startId ? (int) $startId : null;
        return $dto;
    }
}
