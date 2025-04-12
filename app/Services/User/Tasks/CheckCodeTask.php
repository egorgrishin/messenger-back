<?php

namespace App\Services\User\Tasks;

use App\Core\Parents\Task;
use App\Services\User\Models\User;
use Illuminate\Support\Facades\Redis;

final class CheckCodeTask extends Task
{
    /**
     * Проверяет корректность кода для указанного email
     */
    public function run(string $email, string $code): bool
    {
        $key = User::getRedisVerifyCodeKey($email);
        $correctCode = Redis::command('get', [$key]);
        return $code === $correctCode;
    }
}