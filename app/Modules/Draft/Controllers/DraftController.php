<?php
declare(strict_types=1);

namespace Modules\Draft\Controllers;

use Modules\Draft\Actions\CreateOrUpdateDraftAction;
use Modules\Draft\Requests\CreateOrUpdateDraftRequest;
use Core\Parents\Controller;
use Illuminate\Http\JsonResponse;

final class DraftController extends Controller
{
    /**
     * Создает черновик или обновляет его, если он уже существует
     */
    public function createOrUpdateDraft(CreateOrUpdateDraftRequest $request): JsonResponse
    {
        $status = $this->action(CreateOrUpdateDraftAction::class)->run(
            $request->toDto()
        );
        return $this->json([], $status->value);
    }
}
