<?php
declare(strict_types=1);

namespace Modules\User\Models;

use Core\Parents\Model;
use DateTimeInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Data\Factories\UserFactory;

/**
 * @property int $id
 * @property string $nick
 * @property string $password
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property self[] $friends
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

    public function friendRelations(): HasMany
    {
        return $this->hasMany(
            Friendship::class,
            'user_id',
            'id',
        )->where('is_accepted', 1);
    }

    public function subscriptionRelations(): HasMany
    {
        return $this->hasMany(
            Friendship::class,
            'user_id',
            'id',
        )->where('is_accepted', 0);
    }

    public function subscriberRelations(): HasMany
    {
        return $this->hasMany(
            Friendship::class,
            'friend_id',
            'id',
        )->where('is_accepted', 0);
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            Friendship::class,
            'user_id',
            'friend_id',
        )->wherePivot('is_accepted', 1);
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            Friendship::class,
            'user_id',
            'friend_id',
        )->wherePivot('is_accepted', 0);
    }

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            Friendship::class,
            'friend_id',
            'user_id',
        )->wherePivot('is_accepted', 0);
    }
}
