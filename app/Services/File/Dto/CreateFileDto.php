<?php
declare(strict_types=1);

namespace App\Services\File\Dto;

use App\Core\Parents\Dto;
use App\Services\File\Requests\CreateFileRequest;
use Illuminate\Http\UploadedFile;

final readonly class CreateFileDto extends Dto
{
    public string       $uuid;
    public int          $userId;
    public UploadedFile $file;

    public static function fromRequest(CreateFileRequest $request): self
    {
        $dto = new self();
        $dto->uuid = $request->validated('uuid');
        $dto->userId = (int) $request->validated('userId');
        $dto->file = $request->validated('file');
        return $dto;
    }
}
