<?php
declare(strict_types=1);

namespace Modules\Draft\Tasks;

use Core\Parents\Task;
use Modules\Draft\Models\Draft;

final class ClearDraftTask extends Task
{
    /**
     * Очищает черновик после отправки сообщения
     */
    public function run(int $chatId, int $userId): void
    {
        Draft::query()
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->update([
                'text' => null,
            ]);
    }
}
