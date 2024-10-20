<?php

namespace App\Services\User\Channels;

use App\Services\User\Models\User;
use App\Services\User\Tasks\SaveOnlineTask;

class OnlineChannel
{
	/**
	 * Authenticate the user's access to the channel.
	 */
    public function join(User $user, int $userId): bool
    {
		$is_available = $user->id === $userId;
		if ($is_available) {
            (new SaveOnlineTask())->run([$userId], true);
		}
		return $is_available;
    }
}
