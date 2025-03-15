<?php
declare(strict_types=1);

namespace App\Core\Parents;

use App\Core\Concerns\Taskable;

abstract class Event
{
    use Taskable;
}
