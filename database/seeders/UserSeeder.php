<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Services\User\Data\Factories\UserFactory;
use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Services\User\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'nick'     => 'egor',
            'password' => 'egor',
            'email'    => 'egor@grishin.in'
        ]);

        $users = [];
        for ($i = 0; $i < 999; $i++) {
            $users[] = UserFactory::new()->definition();
            if (count($users) >= 100) {
                $this->insert($users);
                $users = [];
            }
        }
        $this->insert($users);
    }


    private function insert(array $users): void
    {
        User::query()->insert($users);
    }
}
