<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class DeleteUserAvatarAction extends Action
{
    public function run(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $filename = $user->avatar_filename;

        try {
            $user->avatar_filename = null;
            $user->save();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }

        Storage::disk('userAvatars')->delete($filename);
    }
}