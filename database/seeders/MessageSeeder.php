<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $topicsCount = 10;
        $openedChats = [];
        $now = now()->toDateTimeString();
        $messages = [];
        $filesCount = count(Storage::disk('test')->files('chats'));
        $lastMessageIds = [];
        $messagesCount = 0;

        for ($i = 1; $i <= 999; $i++) {
            for ($a = 0; $a < $topicsCount; $a++) {
                $randomChatId = rand(1, $filesCount);
                if (!isset($openedChats[$randomChatId])) {
                    $path = Storage::disk('test')->path("chats/$randomChatId.json");
                    $openedChats[$randomChatId] = json_decode(file_get_contents($path));
                }
                $seedMessages = $openedChats[$randomChatId];
                foreach ($seedMessages as [$from, $message]) {
                    $messagesCount++;
                    $userId = $from === 1 ? 1 : $i + 1;

                    $messages[] = [
                        'chat_id'    => $i,
                        'user_id'    => $userId,
                        'text'       => Crypt::encryptString($message),
                        'created_at' => $now,
                    ];
                }
                if ($a !== $topicsCount - 1) {
                    $messagesCount += 2;
                    $messages[] = [
                        'chat_id'    => $i,
                        'user_id'    => 1,
                        'text'       => Crypt::encryptString('--- Смена темы ---'),
                        'created_at' => $now,
                    ];
                    $messages[] = [
                        'chat_id'    => $i,
                        'user_id'    => $i + 1,
                        'text'       => Crypt::encryptString('--- Смена темы ---'),
                        'created_at' => $now,
                    ];
                }
                $lastMessageIds[$i] = $messagesCount;
                if (count($messages) >= 5000) {
                    Message::query()->insert($messages);
                    $messages = [];
                }
            }
        }
        Message::query()->insert($messages);

        foreach ($lastMessageIds as $chatId => $lastMessageId) {
            Chat::query()
                ->where('id', $chatId)
                ->update([
                    'last_message_id' => $lastMessageId,
                ]);
        }
    }
}
