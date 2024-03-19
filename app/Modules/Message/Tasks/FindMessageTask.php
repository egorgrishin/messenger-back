<?php
declare(strict_types=1);

namespace Modules\Message\Tasks;

use Core\Parents\Task;
use Modules\Message\Models\Message;

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
