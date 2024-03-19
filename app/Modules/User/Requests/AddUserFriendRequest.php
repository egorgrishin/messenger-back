<?php
declare(strict_types=1);

namespace Modules\User\Requests;

use Core\Parents\Request;
use Illuminate\Support\Facades\Log;
use Modules\User\Dto\AddUserFriendDto;

final class AddUserFriendRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()?->getAuthIdentifier() == $this->route('userId');
    }

    public function toDto(): AddUserFriendDto
    {
        return AddUserFriendDto::fromRequest($this);
    }
}
