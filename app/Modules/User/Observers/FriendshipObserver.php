<?php
declare(strict_types=1);

namespace Modules\User\Observers;

use Core\Parents\Observer;
use Modules\User\Models\Friendship;
use Modules\User\Tasks\ReplicateFriendshipTask;

final class FriendshipObserver extends Observer
{
    public function updated(Friendship $friendship): void
    {
        $originalIsAccepted = $friendship->getOriginal('is_accepted');
        $isAccepted = $friendship->is_accepted;

        if (!$originalIsAccepted && $isAccepted) {
            $this->task(ReplicateFriendshipTask::class)->run($friendship);
        }
    }
}
