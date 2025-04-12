<?php
declare(strict_types=1);

namespace App\Services\User\Data\Factories;

use Faker\Provider\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\User\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected                $model = User::class;
    protected static ?string $password;
    protected static ?int    $totalAvatarsCount = null;
    protected static ?int    $maleAvatarsCount;
    protected static ?int    $femaleAvatarsCount;
    protected static ?string $now;

    public function definition(): array
    {
        if (self::$totalAvatarsCount === null) {
            self::$maleAvatarsCount ??= count(Storage::disk('userAvatars')->files('test/male'));
            self::$femaleAvatarsCount ??= count(Storage::disk('userAvatars')->files('test/female'));
            self::$totalAvatarsCount = self::$maleAvatarsCount + self::$femaleAvatarsCount;
        }

        $avatarNum = rand(1, self::$totalAvatarsCount);
        if ($avatarNum <= self::$maleAvatarsCount) {
            $avatarFilename = "test/male/$avatarNum.jpg";
            $gender = Person::GENDER_MALE;
        } else {
            $avatarNum -= self::$maleAvatarsCount;
            $avatarFilename = "test/female/$avatarNum.jpg";
            $gender = Person::GENDER_FEMALE;
        }

        return [
            'nick'            => $this->faker->firstName($gender),
            'email'           => $this->faker->unique()->email(),
            'password'        => self::$password ??= 'password',
            'avatar_filename' => $avatarFilename,
            'created_at'      => self::$now ??= now()->toDateTimeString(),
            'updated_at'      => self::$now ??= now()->toDateTimeString(),
        ];
    }
}
