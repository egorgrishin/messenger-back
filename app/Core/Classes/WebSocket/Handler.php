<?php

namespace App\Core\Classes\WebSocket;

use App\Core\Classes\WebSocket\Requests\WebHookRequest;
use App\Services\User\Tasks\SaveOnlineTask;

class Handler
{
    private const DISCONNECT_EVENT = 'channel_vacated';

    /**
     * Обработчик входящих вебхуков от вебсокет сервера
     */
    public function __invoke(WebHookRequest $request): void
    {
        $pattern = '/^private-users\.(\d+)\.online/';
        $userIds = [];
        foreach ($request->input('events') as $event) {
            if ($event['name'] != self::DISCONNECT_EVENT) {
                continue;
            }
            if (!preg_match($pattern, $event['channel'], $matches)) {
                continue;
            }
            $userIds[] = $matches[1];
        }

        if (count($userIds)) {
            (new SaveOnlineTask())->run($userIds, false);
        }
    }
}