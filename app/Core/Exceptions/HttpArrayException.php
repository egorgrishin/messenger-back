<?php
declare(strict_types=1);

namespace App\Core\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;

class HttpArrayException extends HttpResponseException
{
    public function __construct(int $status, array $data)
    {
        parent::__construct(response()->json($data, $status));
    }
}