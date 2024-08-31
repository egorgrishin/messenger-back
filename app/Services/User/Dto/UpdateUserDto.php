<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Core\Parents\Dto;
use Illuminate\Http\UploadedFile;

final readonly class UpdateUserDto extends Dto
{
    public int $id;
    public ?UploadedFile $avatar;

}

/*
 *

0 -> 1 = UploadedFile
0 -> 0 = null
1 -> 0 =
1 -> new 1 = UploadedFile
1 -> old 1 = null

 */