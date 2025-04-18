<?php

namespace App\Services\File\Tasks;

use App\Core\Parents\Task;
use App\Services\File\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class DeleteFileFromStorageTask extends Task
{
    /**
     * Удаляет файл с диска
     */
    public function run(File $file): void
    {
        try {
            $paths = [$file->getFullPath()];
            if ($file->type === File::TYPE_VIDEO) {
                $paths[] = $file->getPreviewFullPath();
            }
            Storage::disk('files')->delete($paths);
        } catch (Throwable $exception) {
            Log::error($exception);
        }
    }
}