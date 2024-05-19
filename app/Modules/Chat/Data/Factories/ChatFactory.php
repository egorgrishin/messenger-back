<?php
declare(strict_types=1);

namespace App\Modules\Chat\Data\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Modules\Chat\Models\Chat;

final class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition(): array
    {
        return [
            'is_dialog' => $isDialog = (bool) rand(0, 1),
            'title'     => $isDialog ? null : Str::random(),
        ];
    }
}
