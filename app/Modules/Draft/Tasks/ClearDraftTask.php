<?php
declare(strict_types=1);

namespace Modules\Draft\Tasks;

use Core\Parents\Task;
use Modules\Draft\Models\Draft;

final class ClearDraftTask extends Task
{
    /**
     * Очищает черновик после отпрравки сообщения
     */
    public function run(int $fromId, int $toId): void
    {
        Draft::query()
            ->where('from_id', $fromId)
            ->where('to_id', $toId)
            ->update([
                'text' => null,
            ]);
    }
}
