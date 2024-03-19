<?php
declare(strict_types=1);

namespace Core\Concerns;

use Core\Parents\Task;

trait Taskable
{
    /**
     * @param class-string<Task> $abstract
     * @return Task
     */
    public function task(string $abstract): Task
    {
        return new $abstract;
    }
}
