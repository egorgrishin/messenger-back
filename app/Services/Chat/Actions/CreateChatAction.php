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
use App\Services\Chat\Models\Chat;
use Throwable;

final class CreateChatAction extends Action
{
    /**
     * Создает новый чат и добавляет в него пользователей
     */
    public function run(int $interlocutorId): Chat
    {
        $this->validate($interlocutorId);

        try {
            return DB::transaction(function () use ($interlocutorId) {
                $chat = new Chat();
                $chat->save();
                return $this->attachUsers($chat, [Auth::id(), $interlocutorId]);
            });
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Проверяет данные перед созданием чата
     */
    private function validate(int $interlocutorId): void
    {
        if ($this->isDialogExists($interlocutorId)) {
            throw new HttpException(422, 'Диалог уже существует');
        }
    }

    /**
     * Проверяет, что диалог между пользователями существует
     */
    private function isDialogExists(int $interlocutorId): bool
    {
        return Chat::query()
            ->whereHas('users', function (Builder $query) use ($interlocutorId) {
                $query->whereIn('users.id', [Auth::id(), $interlocutorId]);
            }, '=', 2)
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