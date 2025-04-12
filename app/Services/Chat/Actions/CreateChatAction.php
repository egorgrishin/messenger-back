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
     * @return array{string, string}
     */
    public function run(int $recipientId): array
    {
        $chat = $this->getChat($recipientId);
        if ($chat) {
            return [false, $chat];
        }

        try {
            return DB::transaction(function () use ($recipientId) {
                $chat = new Chat();
                $chat->save();
                return [true, $this->attachUsers($chat, [Auth::id(), $recipientId])];
            });
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }

    /**
     * Возвращает диалог, в котором состоят пользователи
     */
    private function getChat(int $recipientId): ?Chat
    {
        /** @var ?Chat */
        return Chat::query()
            ->whereHas('users', function (Builder $query) use ($recipientId) {
                $query->whereIn('users.id', [Auth::id(), $recipientId]);
            }, '=', 2)
            ->first();
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