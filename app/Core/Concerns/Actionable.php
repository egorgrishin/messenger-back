<?php
declare(strict_types=1);

namespace App\Core\Concerns;

use App\Core\Parents\Action;

trait Actionable
{
    /**
     * @param class-string<Action> $abstract
     * @return Action
     */
    public function action(string $abstract): Action
    {
        return new $abstract;
    }
}
