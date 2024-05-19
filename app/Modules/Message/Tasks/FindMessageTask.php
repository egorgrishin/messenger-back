<?php
declare(strict_types=1);

namespace App\Modules\Message\Tasks;

use App\Core\Parents\Task;
use App\Modules\Message\Models\Message;

final class FindMessageTask extends Task
{
    /**
     * Возвращает сообщение по ID
     */
    public function run(int $messageId): ?Message
    {
        /** @var Message|null */
        return Message::query()->find($messageId);
    }
}
