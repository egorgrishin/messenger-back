<?php
declare(strict_types=1);

namespace Core\Enums;

enum HttpStatus: int
{
    case Ok = 200;
    case Created = 201;
    case NoContent = 204;
}
