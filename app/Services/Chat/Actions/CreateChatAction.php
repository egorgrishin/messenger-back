<?php
declare(strict_types=1);

namespace App\Services\Chat\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\Chat\Events\ChatUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Chat\Dto\CreateChatDto;
use App\Services\Chat\Models\Chat;
use Throwable;

final class CreateChatAction extends Action
{
    /**
     * Создает новый чат и добавляет в него пользователей
     */
    public function run(CreateChatDto $dto): Chat
    {
        $this->validate($dto);

        try {
            return DB::transaction(function () use ($dto) {
                $chat = new Chat();
                $chat->save();
                return $this->attachUsers($chat, $dto->users);
            });
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
        if (!in_array(Auth::id(), $dto->users)) {
            throw new HttpException(422, 'Вы должны присутствовать в списке пользователей');
        }
        if ($this->isDialogExists($dto)) {
            throw new HttpException(422, 'Диалог уже существует');
        }
    }

    /**
     * Проверяет, что диалог между пользователями существует
     */
    private function isDialogExists(CreateChatDto $dto): bool
    {
        return Chat::query()
            ->whereHas('users', function (Builder $query) use ($dto) {
                $query->whereIn('users.id', $dto->users);
            }, '=', count($dto->users))
            ->exists();
    }

    /**
     * Прикрепляет пользователей к чату
     */
    public function attachUsers(Chat $chat, array $users): Chat
    {
        $chat->users()->attach($users);
        foreach ($users as $userId) {
            ChatUpdated::dispatch($userId, $chat);
        }
        return $chat;
    }
}