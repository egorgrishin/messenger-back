<?php
declare(strict_types=1);

use App\Services\User\Channels\OnlineChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('users.{userId}.online', OnlineChannel::class);
