<?php
declare(strict_types=1);

namespace App\Services\Message\Data\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Services\Message\Models\Message;

final class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'text' => Str::random(),
        ];
    }
}
