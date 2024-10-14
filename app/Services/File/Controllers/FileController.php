<?php
declare(strict_types=1);

namespace App\Services\File\Controllers;

use App\Core\Parents\Controller;
use App\Services\File\Actions\CreateFileAction;
use App\Services\File\Requests\CreateFileRequest;
use App\Services\File\Resources\FileResource;
use Illuminate\Http\JsonResponse;

final class FileController extends Controller
{
    /**
     * Сохраняет в хранилище сжатое изображение и записывает информацию о нем в базу данных
     */
    public function create(CreateFileRequest $request): JsonResponse
    {
        $file = $this->action(CreateFileAction::class)->run(
            $request->toDto()
        );

        return $this
            ->resource($file, FileResource::class)
            ->response()
            ->setStatusCode(201);
    }
}