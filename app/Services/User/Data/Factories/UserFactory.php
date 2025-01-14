<?php
declare(strict_types=1);

namespace App\Services\User\Data\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\User\Models\User;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected $model = User::class;
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'login'    => $this->faker->unique()->userName(),
            'nick'     => $this->faker->unique()->userName(),
            'password' => UserFactory::$password ??= 'password',
        ];
    }
}
