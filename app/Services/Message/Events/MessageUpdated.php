<?php

namespace App\Services\Message\Events;

use App\Core\Parents\Event;
use App\Services\Message\Models\Message;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageUpdated extends Event
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Message $message
    ) {}
}
