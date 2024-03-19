<?php
declare(strict_types=1);

namespace Modules\User\Tests;

use Core\Parents\Test;
use Illuminate\Support\Str;
use Modules\User\Models\User;

final class CreateUserTest extends Test
{
    public function testCreateUser(): void
    {
        $this->post('/api/v1/users', [
            'nick'                  => $nick = Str::random(),
            'password'              => $pass = Str::random(),
            'password_confirmation' => $pass,
        ])->assertCreated();

        $this->assertDatabaseHas(User::class, [
            'nick' => $nick,
        ])->assertDatabaseCount(User::class, 1);
    }
}
