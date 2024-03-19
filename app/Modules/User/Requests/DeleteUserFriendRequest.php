<?php
declare(strict_types=1);

namespace Modules\User\Requests;

use Core\Parents\Request;
use Modules\User\Dto\DeleteUserFriendDto;

final class DeleteUserFriendRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()?->getAuthIdentifier() == (int) $this->route('userId');
    }

    public function toDto(): DeleteUserFriendDto
    {
        return DeleteUserFriendDto::fromRequest($this);
    }
}
