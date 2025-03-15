<?php

namespace App\Services\File\Jobs;

use App\Services\File\Classes\Deleter\Deleter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteFiles implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly array $files
    ) {}

    public function handle(): void
    {
        foreach ($this->files as $file) {
            Deleter::run($file['userId'], $file['type'], $file['filename']);
        }
    }
}
