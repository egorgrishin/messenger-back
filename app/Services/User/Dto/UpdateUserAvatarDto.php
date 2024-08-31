<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Core\Parents\Dto;
use App\Services\User\Requests\UpdateUserAvatarRequest;
use Illuminate\Http\UploadedFile;

final readonly class UpdateUserAvatarDto extends Dto
{
    public int          $userId;
    public UploadedFile $avatar;

    public static function fromRequest(UpdateUserAvatarRequest $request): self
    {
        $dto = new self();
        $dto->userId = (int) $request->route('userId');
        $dto->avatar = $request->file('avatar');
        return $dto;
    }
}