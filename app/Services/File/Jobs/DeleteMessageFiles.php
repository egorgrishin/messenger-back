<?php

namespace App\Services\File\Jobs;

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
        File::query()
            ->where('message_id', $this->messageId)
            ->get()
            ->each(fn (File $file) => $file->delete());
    }
}
