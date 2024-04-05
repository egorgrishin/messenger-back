<?php
declare(strict_types=1);

namespace Modules\Draft\Actions;

use Core\Enums\HttpStatus;
use Modules\Draft\Dto\CreateOrUpdateDraftDto;
use Modules\Draft\Models\Draft;
use Core\Parents\Action;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class CreateOrUpdateDraftAction extends Action
{
    /**
     * Создает черновик или обновляет его, если он уже существует
     */
    public function run(CreateOrUpdateDraftDto $dto): HttpStatus
    {
        if (($draft = $this->getDraft($dto)) === null) {
            $this->createDraft($dto);
            return HttpStatus::Created;
        }

        $this->updateDraft($draft, $dto->text);
        return HttpStatus::NoContent;
    }

    /**
     * Возвращает черновик
     */
    private function getDraft(CreateOrUpdateDraftDto $dto): ?Draft
    {
        /** @var Draft|null */
        return Draft::query()
            ->where('chat_id', $dto->chatId)
            ->where('user_id', $dto->userId)
            ->first();
    }

    /**
     * Создает черновик
     */
    private function createDraft(CreateOrUpdateDraftDto $dto): void
    {
        try {
            $draft = new Draft();
            $draft->chat_id = $dto->chatId;
            $draft->user_id = $dto->userId;
            $draft->text = $dto->text;
            $draft->saveOrFail();
        } catch (Throwable) {
            throw new HttpException(500);
        }
    }

    /**
     * Обновляет черновик
     */
    private function updateDraft(Draft $draft, ?string $text): void
    {
        try {
            $draft->text = $text;
            $draft->save();
        } catch (Throwable) {
            throw new HttpException(500);
        }
    }
}
