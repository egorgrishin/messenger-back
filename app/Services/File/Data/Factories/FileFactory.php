<?php
declare(strict_types=1);

namespace App\Services\File\Data\Factories;

use App\Services\Message\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FileFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            //
        ];
    }
}
