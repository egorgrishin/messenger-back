<?php

namespace App\Services\File\Cron;

use App\Services\File\Models\File;
use Illuminate\Support\Collection;

final class DeleteUnusedFiles
{
    private const FILES_IN_CHUNK = 100;

    public function __invoke(): void
    {
        File::query()
            ->whereNull('message_id')
            ->where('created_at', '<=', now()->subDay()->toDateTimeString())
            ->chunk(self::FILES_IN_CHUNK, $this->getChunkCallback());
    }

    private function getChunkCallback(): callable
    {
        return static function (Collection $files) {
            $files->each(static fn (File $file) => $file->delete());
        };
    }
}
