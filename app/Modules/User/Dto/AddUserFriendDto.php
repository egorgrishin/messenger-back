<?php
declare(strict_types=1);

namespace Modules\User\Dto;

use Core\Parents\Dto;
use Modules\User\Requests\AddUserFriendRequest;

final readonly class AddUserFriendDto extends Dto
{
    public int $userId;
    public int $friendId;

    public static function fromRequest(AddUserFriendRequest $request): self
    {
        $dto = new self();
        $dto->userId = (int) $request->route('userId');
        $dto->friendId = (int) $request->route('friendId');
        return $dto;
    }
}
