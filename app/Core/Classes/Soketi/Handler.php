<?php

namespace App\Core\Classes\Soketi;

use App\Core\Classes\Soketi\Events\ChannelAction;
use App\Core\Classes\Soketi\Requests\WebHookRequest;
use App\Core\Classes\Soketi\Tasks\SaveOnlineTask;
use App\Core\Exceptions\StringHttpException;
use App\Core\Parents\BaseClass;
use App\Sections\TrainerSection\Trainer\Actions\RemoveUserFromQueueAction;

class Handler extends BaseClass
{
    private const DISCONNECT_EVENT = 'channel_vacated';

    /**
     * Обработчик входящих вебхуков от Soketi
     */
    public function __invoke(WebHookRequest $request)
    {
        $pattern = '/^private-online\.user\.(\d+)/i';
        $count = 0;
        foreach ($request->input('events') as $event) {
            if ($event['name'] != self::DISCONNECT_EVENT) {
                continue;
            }
            if (preg_match($pattern, $event['channel'], $matches) === 0) {
                continue;
            }
            $user_id = $matches[1];
            $count++;

            ChannelAction::dispatch($user_id, 'close_channel');
            $this->task(SaveOnlineTask::class)->run($user_id, 0);
            $this->action(RemoveUserFromQueueAction::class)->run($user_id);
        }

        if ($count === 0) {
            throw new StringHttpException("Disconnect event [online] not found", 422);
        }
    }
}