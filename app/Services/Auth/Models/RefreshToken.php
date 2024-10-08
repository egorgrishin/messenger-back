<?php
declare(strict_types=1);

namespace App\Services\Auth\Models;

use App\Core\Parents\Model;
use DateTimeInterface;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * @property string $ulid
 * @property string $chain
 * @property int $user_id
 * @property bool $is_blocked
 * @property string $ip_address
 * @property string|null $user_agent
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $expired_in
 */
final class RefreshToken extends Model
{
    protected $primaryKey = 'ulid';

    public $incrementing = false;

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'is_blocked' => 'boolean',
            'expired_in' => 'datetime',
        ];
    }

    /**
     * Блокирует токен
     */
    public function block(): void
    {
        try {
            $this->is_blocked = true;
            $this->saveOrFail();
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500);
        }
    }
}
