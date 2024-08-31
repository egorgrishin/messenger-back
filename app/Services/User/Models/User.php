<?php
declare(strict_types=1);

namespace App\Services\User\Models;

use App\Core\Parents\Model;
use App\Services\Chat\Models\Chat;
use App\Services\User\Data\Factories\UserFactory;
use App\Services\User\Exceptions\SaveAvatarException;
use DateTimeInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $nick
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
    public function getAvatarLinkAttribute(): ?string
    {
        return $this->avatar_filename
            ? $this->avatar_filename
            : null;
    }

    /**
     * @throws \Exception
     */
    public function saveAvatar(?UploadedFile $avatar): void
    {
        if ($avatar === null) {
            return;
        }
        $filename = Storage::disk('userAvatars')->putFile($avatar);
        if ($filename === false) {
            throw new SaveAvatarException();
        }
        $this->avatar_filename = $filename;
    }

    public function deleteAvatar(): void
    {
        if ($this->avatar_filename) {
            Storage::disk('userAvatars')->delete($this->avatar_filename);
        }
    }

    private function getAvatarFilename()
    {

    }
}
