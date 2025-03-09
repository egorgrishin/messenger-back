<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class UpdateUserAvatarAction extends Action
{
    public function run(UploadedFile $avatar): void
    {
        /** @var User $user */
        $user = Auth::user();
        $oldFilename = $user->avatar_filename;

        try {
            $user->saveAvatar($avatar);
            $newFilename = $user->avatar_filename;
            $user->save();
        } catch (Throwable $exception) {
            Log::error($exception);
            if (!empty($newFilename)) {
                Storage::disk('userAvatars')->delete($newFilename);
            }
            throw new HttpException(500);
        }

        if ($oldFilename) {
            Storage::disk('userAvatars')->delete($oldFilename);
        }
    }
}