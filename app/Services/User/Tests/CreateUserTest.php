<?php
declare(strict_types=1);

namespace App\Services\User\Tests;

use App\Core\Parents\Test;
use App\Services\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

final class CreateUserTest extends Test
{
//    public function testCreateUserWithoutAvatar(): void
//    {
//        $this
//            ->postJson('/api/v1/users', [
//                'nick'                  => $nick = Str::random(),
//                'email'                 => $email = Str::random() . '@test.dev',
//                'password'              => $pass = Str::random(),
//                'password_confirmation' => $pass,
//            ])
//            ->assertCreated()
//            ->assertJsonStructure([
//                'data' => [
//                    'id',
//                    'nick',
//                    'avatarUrl',
//                ],
//            ])
//            ->assertJson(fn (AssertableJson $json) => $json
//                ->has('data.id')
//                ->where('data.nick', $nick)
//                ->where('data.avatarUrl', null)
//            );
//
//        $this->assertDatabaseHas(User::class, [
//            'nick'  => $nick,
//            'email' => $email,
//        ]);
//    }
}
