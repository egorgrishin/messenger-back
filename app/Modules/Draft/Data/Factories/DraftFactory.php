<?php
declare(strict_types=1);

namespace Modules\Draft\Data\Factories;

use Modules\Draft\Models\Draft;
use Illuminate\Database\Eloquent\Factories\Factory;

final class DraftFactory extends Factory
{
    protected $model = Draft::class;

    public function definition(): array
    {
        return [
            'text' => $this->faker->text(),
        ];
    }
}
