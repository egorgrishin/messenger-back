<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Services\User\Dto\CreateUserDto;
use App\Services\User\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

final class CreateUserAction extends Action
{
    /**
     * Создает нового пользователя
     */
    public function run(CreateUserDto $dto): array
    {
        try {
            $user = new User();
            $user->nick = $dto->nick;
            $user->password = $dto->password;
            $user->saveAvatar($dto->avatar);
            $user->saveOrFail();
            return $user->toArray();
        } catch (Throwable $exception) {
            Log::error($exception);
            if (!empty($user)) {
                $user->deleteAvatar();
            }
            throw new HttpException(500);
        }
    }
}
