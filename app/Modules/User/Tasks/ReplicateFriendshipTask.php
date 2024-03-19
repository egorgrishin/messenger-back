<?php
declare(strict_types=1);

namespace Modules\User\Tasks;

use Core\Parents\Task;
use Modules\User\Models\Friendship;

final class ReplicateFriendshipTask extends Task
{
    public function run(Friendship $friendship): void
    {
        $copy = $friendship->replicate();
        $copy->user_id = $friendship->friend_id;
        $copy->friend_id = $friendship->user_id;
        $copy->save();
    }
}
