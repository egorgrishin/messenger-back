<?php
declare(strict_types=1);

namespace App\Services\File\Observers;

use App\Core\Parents\Observer;
use App\Services\File\Classes\Deleter\Deleter;
use App\Services\File\Models\File;
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
        (new Deleter($file))->run();
    }
}