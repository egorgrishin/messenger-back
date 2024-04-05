<?php
declare(strict_types=1);

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\User\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Factory::create()->unique();
        $now = now()->toDateTimeString();
        User::factory()->create([
            'nick'     => 'egor',
            'password' => 'egor',
        ]);

        $users = [];
        for ($i = 0; $i < 999; $i++) {
            $users[] = [
                'nick' => $faker->userName(),
                'password' => 'password',
                'created_at' => $now,
                'updated_at' => $now,
            ];
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
