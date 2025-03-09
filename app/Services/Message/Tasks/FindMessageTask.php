<?php
declare(strict_types=1);

namespace App\Services\Message\Tasks;

use App\Core\Parents\Task;
use App\Services\Message\Models\Message;
use App\Services\User\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class FindMessageTask extends Task
{
    /**
     * Возвращает сообщение по ID
     * @throws ModelNotFoundException<User>
     */
    public function run(int $messageId): Message
    {
        return Message::query()->findOrFail($messageId);
    }
}
