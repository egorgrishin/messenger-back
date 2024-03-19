<?php

namespace Core\Parents;

use Core\Concerns\Actionable;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    use Actionable;

    /**
     * Create a new JSON response instance.
     */
    protected function json(
        mixed $data = [],
        int $status = 200,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return response()->json($data, $status, $headers, $options);
    }
}
