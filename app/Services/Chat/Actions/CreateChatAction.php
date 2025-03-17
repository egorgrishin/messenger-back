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
    public function run(int $recipientId): Chat
    {
        $this->validate($recipientId);

        try {
            return DB::transaction(function () use ($recipientId) {
                $chat = new Chat();
                $chat->save();
                return $this->attachUsers($chat, [Auth::id(), $recipientId]);
            });
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Проверяет данные перед созданием чата
     */
    private function validate(int $recipientId): void
    {
        if ($this->isDialogExists($recipientId)) {
            throw new HttpException(422, 'Диалог уже существует');
        }
    }

    /**
     * Проверяет, что диалог между пользователями существует
     */
    private function isDialogExists(int $recipientId): bool
    {
        return Chat::query()
            ->whereHas('users', function (Builder $query) use ($recipientId) {
                $query->whereIn('users.id', [Auth::id(), $recipientId]);
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