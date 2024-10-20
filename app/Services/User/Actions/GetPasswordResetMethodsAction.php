<?php
declare(strict_types=1);

namespace App\Services\User\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\User\Models\User;

final class GetPasswordResetMethodsAction extends Action
{
    /**
     * Возвращает способы, которыми пользователь может восстановить пароль от аккаунта.
     * Если доступно восстановление по электронному письму, то также вернется и адрес электронной почту, закрытый звездочками.
     * Если доступно восстановление по кодовому слову, то также вернется подсказка к нему.
     */
    public function run(int $userId): array
    {
        /** @var User|null $user */
        $user = User::query()
            ->select([
                'id',
                'email',
                'code_word',
                'code_hint',
            ])
            ->find($userId);

        if (!$user) {
            throw new HttpException(404);
        }

        return [
            'email' => $user->masked_email ?: false,
            'word'  => $user->code_word ? $user->code_hint : false,
        ];
    }
}
