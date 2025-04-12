<?php

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpArrayException;
use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\User\Mail\VerifyMail;
use App\Services\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

final class SendCodeAction extends Action
{
    private const TTL = 10 * 60;
    private const RETRY = 2 * 60;

    /**
     * Генерирует код подтверждения, сохраняет его в память и отправляет пользователю на почту
     */
    public function run(?string $email): int
    {
        $email = $this->getEmail($email);
        $key = User::getRedisVerifyCodeKey($email);
        $passed = self::TTL - Redis::command('ttl', [$key]);

        if ($passed < self::RETRY) {
            throw new HttpArrayException(403, [
                'passed' => $passed,
                'retry'  => self::RETRY,
                'code'   => 'TIME_NOT_PASSED',
            ]);
        }

        $code = rand(1000, 9999);
        $mail = (new VerifyMail($code))
            ->onConnection(env('QUEUE_CONNECTION', 'redis'))
            ->onQueue('email');

        if (!Mail::to($email)->queue($mail)) {
            throw new HttpException(500);
        }

        Redis::command('set', [$key, $code, ['EX' => self::TTL]]);
        return self::RETRY;
    }

    /**
     * Возвращает email для которого нужно осуществить сбррос пароля
     */
    private function getEmail(?string $email): string
    {
        if (Auth::hasUser()) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            return Auth::user()->email;
        }
        if (!$email) {
            throw new HttpException(422, 'Некорректный адрес электронной почты');
        }
        return $email;
    }
}