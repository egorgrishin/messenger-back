<?php

namespace App\Services\Message\Events;

use App\Services\Message\Models\Message;
use Illuminate\Foundation\Events\Dispatchable;

final class MessageDeleted
{
    use Dispatchable;

    public function __construct(
        public readonly Message $message
    ) {}
}
