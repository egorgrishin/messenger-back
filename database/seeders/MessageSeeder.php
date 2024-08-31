<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Chat\Models\Chat;
use App\Services\Message\Models\Message;

class MessageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now()->toDateTimeString();
        $messages = [];
        for ($i = 1; $i <= 999; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $messages[] = [
                    'chat_id' => $i,
                    'user_id' => mt_rand(0, 1) ? 1 : $i + 1,
                    'text'    => Str::random(),
                    'created_at' => $now,
                ];
            }
            if (count($messages) >= 1000) {
                Message::query()->insert($messages);
                $messages = [];
            }
        }
        Message::query()->insert($messages);
        Chat::query()->update([
            'last_message_id' => DB::raw('(id - 1) * 10 + 9'),
        ]);
    }
}
