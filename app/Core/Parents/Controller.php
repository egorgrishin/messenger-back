<?php

namespace Core\Parents;

use Core\Concerns\Actionable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    /**
     * Returns JsonResource of item
     *
     * @param array|object $item
     * @param class-string<JsonResource> $resource
     * @return JsonResource
     */
    protected function resource(array|object $item, string $resource): JsonResource
    {
        return new $resource($item);
    }

    /**
     * Returns JsonCollection of items
     *
     * @param iterable $items
     * @param class-string<JsonResource> $resource
     * @return AnonymousResourceCollection
     */
    protected function collection(iterable $items, string $resource): AnonymousResourceCollection
    {
        $resources = [];
        foreach ($items as $item) {
            $resources[] = new $resource($item);
        }
        return $resource::collection($resources);
    }
}
