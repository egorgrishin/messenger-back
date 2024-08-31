<?php
declare(strict_types=1);

namespace App\Services\User\Models;

use App\Core\Parents\Model;
use App\Services\Chat\Models\Chat;
use App\Services\User\Data\Factories\UserFactory;
use DateTimeInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $login
 * @property string $nick
 * @property string|null $short_link
 * @property string|null $email
 * @property string|null $status
 * @property string|null $code_word
 * @property string|null $code_hint
 * @property string|null $avatar_filename
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

    protected $appends = [
        'avatar_url',
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

    /** @noinspection PhpUnused */
    protected function avatarUrl(): Attribute
    {
        $getter = function () {
            return $this->avatar_filename
                ? $this->avatar_filename
                : null;
        };

        return new Attribute(get: $getter);
    }

    /**
     * Сохраняет файл аватара в хранилище и записывает путь к нему
     */
    public function saveAvatar(?UploadedFile $avatar): void
    {
        if (!$avatar) {
            return;
        }

        $filename = Storage::disk('userAvatars')->putFile($avatar);
        $this->avatar_filename = $filename ?: null;
    }

    public function deleteAvatar(): void
    {
        if ($this->avatar_filename) {
            Storage::disk('userAvatars')->delete($this->avatar_filename);
        }
    }
}
