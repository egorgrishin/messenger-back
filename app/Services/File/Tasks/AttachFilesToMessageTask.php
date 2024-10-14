<?php
declare(strict_types=1);

namespace App\Services\File\Tasks;

use App\Core\Parents\Task;
use App\Services\File\Models\File;

final class AttachFilesToMessageTask extends Task
{
    /**
     * Заполняет в сообщениях поле message_id
     */
    public function run(int $messageId, array $fileUuids): void
    {
        File::query()
            ->whereIn('uuid', $fileUuids)
            ->update(['message_id' => $messageId]);
    }
}