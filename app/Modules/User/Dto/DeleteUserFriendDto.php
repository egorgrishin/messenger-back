<?php
declare(strict_types=1);

namespace Modules\User\Dto;

use Core\Parents\Dto;
use Modules\User\Requests\DeleteUserFriendRequest;

final readonly class DeleteUserFriendDto extends Dto
{
    public int $userId;
    public int $friendId;

    public static function fromRequest(DeleteUserFriendRequest $request): self
    {
        $dto = new self();
        $dto->userId = (int) $request->route('userId');
        $dto->friendId = (int) $request->route('friendId');
        return $dto;
    }
}
