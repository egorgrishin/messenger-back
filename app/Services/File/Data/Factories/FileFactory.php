<?php
declare(strict_types=1);

namespace App\Services\File\Data\Factories;

use App\Services\File\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        return [
            'uuid'            => Str::uuid()->toString(),
            'filename'        => Str::random() . '.jpg',
            'client_filename' => Str::random() . '.jpg',
            'type'            => 3,
            'created_at'      => now(),
        ];
    }
}
