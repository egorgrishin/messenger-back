<?php
declare(strict_types=1);

namespace Modules\Chat\Actions;

use Core\Exceptions\HttpException;
use Core\Parents\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Chat\Dto\CreateChatDto;
use Modules\Chat\Models\Chat;
use Throwable;

final class CreateChatAction extends Action
{
    /**
     * Создает новый чат и добавляет в него пользователей, если они указаны
     */
    public function run(CreateChatDto $dto): array
    {
        $this->validate($dto);

        try {
            /** @var Chat $chat */
            $chat = DB::transaction(function () use ($dto) {
                return $this->createChat($dto)->attachUsers($dto->users ?? []);
            });
            return $chat->toArray();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Проверяет данные перед созданием чата
     */
    private function validate(CreateChatDto $dto): void
    {
        if ($dto->isDialog && $this->isDialogExists($dto)) {
            throw new HttpException(422, 'Диалог уже существует');
        }
    }

    /**
     * Проверяет, что диалог между пользователями существует
     */
    private function isDialogExists(CreateChatDto $dto): bool
    {
        return Chat::query()
            ->where('is_dialog', 1)
            ->whereHas('users', function (Builder $query) use ($dto) {
                $query->whereIn('users.id', $dto->users);
            }, '=', count($dto->users))
            ->exists();
    }

    /**
     * Добавляет чат в базу данных
     * @throws Throwable
     */
    private function createChat(CreateChatDto $dto): Chat
    {
        $chat = new Chat();
        $chat->title = $dto->title;
        $chat->is_dialog = $dto->isDialog;
        $chat->saveOrFail();
        return $chat;
    }
}