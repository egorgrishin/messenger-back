<?php
declare(strict_types=1);

namespace Core\Parents;

use Core\Concerns\Taskable;

abstract class Observer
{
    use Taskable;
}
