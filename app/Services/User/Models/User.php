<?php
declare(strict_types=1);

namespace App\Services\User\Models;

use App\Core\Parents\Model;
use App\Services\Chat\Models\Chat;
use App\Services\User\Data\Factories\UserFactory;
use App\Services\User\Dto\CreateUserDto;
use DateTimeInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * @property int $id
 * @property string $nick
 * @property string $email
 * @property bool $is_online
 * @property string|null $avatar_filename
 * @property string $password
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 *
 * @property string|null $avatar_url
 * @property string|null $masked_email
 *
 * @extends HasFactory<UserFactory>
 */
final class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasFactory;

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'avatar_url',
        'masked_email',
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
                ? Storage::disk('userAvatars')->url($this->avatar_filename)
                : null;
        };

        return new Attribute(get: $getter);
    }

    /** @noinspection PhpUnused */
    protected function maskedEmail(): Attribute
    {
        $getter = function () {
            return $this->email
                ? Str::mask($this->email, '*', 3, strripos($this->email, '@') - 3)
                : null;
        };

        return new Attribute(get: $getter);
    }

    /**
     * Добавляет нового пользователя в базу данных и возвращает модель.
     * @throws Throwable
     */
    public static function create(CreateUserDto $dto): self
    {
        $user = new self();
        $user->email = $dto->email;
        $user->password = $dto->password;
        $user->nick = $dto->nick;
        $user->saveOrFail();

        return $user;
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

    /**
     * Возвращает ключ Redis, под которым лежит код для подтверждения адреса электронной почты
     */
    public static function getRedisVerifyCodeKey(string $email): string
    {
        return "verify-code.$email";
    }
}
