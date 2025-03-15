<?php

namespace App\Services\File\Jobs;

use App\Services\File\Classes\Deleter\Deleter;
use App\Services\File\Models\File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteMessageFiles implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly int $messageId
    ) {}

    public function handle(): void
    {
        $files = File::query()
            ->where('message_id', $this->messageId)
            ->get();

        /** @var File $file */
        foreach ($files as $file) {
            Deleter::run($file->user_id, $file->type, $file->filename);
        }

        File::query()
            ->where('message_id', $this->messageId)
            ->delete();
    }
}
