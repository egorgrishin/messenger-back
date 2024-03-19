<?php
declare(strict_types=1);

namespace Modules\User\Data\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

final class UserFactory extends Factory
{
    protected $model = User::class;
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nick'     => $this->faker->unique()->userName(),
            'password' => UserFactory::$password ??= 'password',
        ];
    }
}
