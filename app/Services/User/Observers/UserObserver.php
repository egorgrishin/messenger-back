<?php

namespace App\Services\User\Observers;

use App\Core\Parents\Observer;
use App\Services\User\Models\User;

class UserObserver extends Observer
{
    public function created(User $user): void
    {

    }
}