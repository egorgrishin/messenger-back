<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Models\Chat;

class ChatSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $chats = [];
        $chat_user = [];
        for ($i = 1; $i <= 999; $i++) {
            $chats[] = ['id' => $i];
            if (mt_rand(0, 1)) {
                $chat_user[] = ['chat_id' => $i, 'user_id' => 1];
                $chat_user[] = ['chat_id' => $i, 'user_id' => $i + 1];
            } else {
                $chat_user[] = ['chat_id' => $i, 'user_id' => $i + 1];
                $chat_user[] = ['chat_id' => $i, 'user_id' => 1];
            }
            if (count($chats) >= 100) {
                Chat::query()->insert($chats);
                DB::table('chat_user')->insert($chat_user);
                $chats = [];
                $chat_user = [];
            }
        }
        Chat::query()->insert($chats);
        DB::table('chat_user')->insert($chat_user);
    }
}
