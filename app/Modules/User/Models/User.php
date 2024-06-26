<?php
declare(strict_types=1);

namespace App\Modules\User\Models;

use App\Core\Parents\Model;
use DateTimeInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Modules\Chat\Models\Chat;
use App\Modules\User\Data\Factories\UserFactory;

/**
 * @property int $id
 * @property string $nick
 * @property string $password
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 */
final class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasFactory;

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class);
    }
}
