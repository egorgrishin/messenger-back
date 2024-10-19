<?php

namespace App\Core\Classes\Soketi\Channels;

use App\Core\Classes\Soketi\Events\ChannelAction;
use App\Core\Classes\Soketi\Tasks\SaveOnlineTask;
use App\Core\Parents\BaseClass;
use App\Sections\UserSection\User\Models\User;

class OnlineChannel extends BaseClass
{
	/**
	 * Authenticate the user's access to the channel.
	 */
    public function join(User $user, int $user_id): bool
    {
		$is_available = $user->id === $user_id;
		if ($is_available) {
            // Включить статус
            ChannelAction::dispatch($user_id, 'open_channel');
            $this->task(SaveOnlineTask::class)->run($user_id, 1);
		}
		return $is_available;
    }
}
