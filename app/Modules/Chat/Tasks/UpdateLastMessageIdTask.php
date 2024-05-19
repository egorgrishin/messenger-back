<?php
declare(strict_types=1);

namespace App\Modules\Chat\Tasks;

use App\Core\Parents\Task;
use App\Modules\Chat\Models\Chat;

class UpdateLastMessageIdTask extends Task
{
    /**
     * Обновляет ID последнего сообщения в чате
     */
    public function run(int $chatId, int $messageId): void
    {
        Chat::query()->where('id', $chatId)->update([
            'last_message_id' => $messageId,
        ]);
    }
}