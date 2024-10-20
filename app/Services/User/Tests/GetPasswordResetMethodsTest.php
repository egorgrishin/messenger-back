<?php

namespace App\Services\User\Tests;

use App\Core\Parents\Test;
use App\Services\User\Models\User;

final class GetPasswordResetMethodsTest extends Test
{
    public function testGetPasswordResetMethods(): void
    {
        /** @var User[] $users */
        $users = [
            User::factory()->create(),
            User::factory()->create([
                'email' => 'test-1-email@test.com',
            ]),
            User::factory()->create([
                'code_word' => 'test-code-word',
            ]),
            User::factory()->create([
                'code_word' => 'test-code-word',
                'code_hint' => 'test-code-hint',
            ]),
            User::factory()->create([
                'email'     => 'test-2-email@test.com',
                'code_word' => 'test-code-word',
                'code_hint' => 'test-code-hint',
            ]),
        ];

        foreach ($users as $user) {
            $this
                ->getJson("/api/v1/users/$user->id/reset-methods")
                ->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'email',
                        'word',
                    ],
                ])
                ->assertJson([
                    'data' => [
                        'email' => $user->masked_email ?: false,
                        'word'  => $user->code_word ? $user->code_hint : false,
                    ],
                ]);
        }
    }
}