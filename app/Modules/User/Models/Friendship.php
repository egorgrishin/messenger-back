<?php
declare(strict_types=1);

namespace Modules\User\Models;

use Core\Parents\Pivot;

/**
 * @property int $id
 * @property int $user_id
 * @property int $friend_id
 * @property bool $is_accepted
 */
final class Friendship extends Pivot
{
    protected $table = 'friendship';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_accepted' => 'boolean',
        ];
    }

    public function accept(): void
    {
        $this->is_accepted = 1;
        $this->save();
    }
}
