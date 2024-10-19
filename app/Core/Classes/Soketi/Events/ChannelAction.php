<?php

namespace App\Core\Classes\Soketi\Events;

use App\Sections\UserSection\Activity\Events\CreateActivityEvent;
use Illuminate\Support\Carbon;

class ChannelAction extends CreateActivityEvent
{
    public function __construct(int $user_id, string $type)
    {
        $this->user_id = $user_id;
        $this->type = $type;
        $this->is_interval = false;
        $this->start_date = Carbon::now();
    }
}
