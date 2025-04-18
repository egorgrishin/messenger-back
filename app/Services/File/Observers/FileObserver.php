<?php
declare(strict_types=1);

namespace App\Services\File\Observers;

use App\Core\Parents\Observer;
use App\Services\File\Models\File;
use App\Services\File\Tasks\DeleteFileFromStorageTask;
use Illuminate\Support\Str;

class FileObserver extends Observer
{
    public function creating(File $file): void
    {
        if (!$file->uuid) {
            $file->uuid = Str::uuid()->toString();
        }
    }

    public function deleted(File $file): void
    {
        $this->task(DeleteFileFromStorageTask::class)->run($file);
    }
}