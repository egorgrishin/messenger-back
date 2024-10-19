<?php
declare(strict_types=1);

namespace App\Core\Classes\Soketi\Tasks;

use App\Core\Parents\BaseTask;
use Illuminate\Support\Facades\DB;

class SaveOnlineTask extends BaseTask
{
    /**
     * ССохраняет статус пользователя
     */
    public function run(int $user_id, int $is_online): void
    {
        DB::table('users')
            ->where('id', $user_id)
            ->update(['is_online' => $is_online]);
    }
}