<?php
declare(strict_types=1);

namespace App\Services\File\Observers;

use App\Core\Parents\Observer;
use App\Services\File\Models\File;
use Illuminate\Support\Str;
use Random\RandomException;

class FileObserver extends Observer
{
    /**
     * @throws RandomException
     */
    public function creating(File $file): void
    {
        $file->uuid = Str::uuid()->toString();
        $file->sign = bin2hex(random_bytes($file::SIGN_BYTES));
    }
}