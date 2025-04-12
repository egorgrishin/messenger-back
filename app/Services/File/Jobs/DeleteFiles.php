<?php

namespace App\Services\File\Jobs;

use App\Services\File\Classes\Deleter\Deleter;
use App\Services\File\Models\File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DeleteFiles implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly Collection $files
    ) {}

    public function handle(): void
    {
        File::query()
            ->whereIn('uuid', $this->files->pluck('uuid')->toArray())
            ->delete();

        foreach ($this->files as $file) {
            Deleter::run($file->user_id, $file->type, $file->filename);
        }
    }
}
