<?php
declare(strict_types=1);

namespace Modules\Draft\Requests;

use Modules\Chat\Models\Chat;
use Modules\Draft\Dto\CreateOrUpdateDraftDto;
use Core\Parents\Request;
use Modules\User\Models\User;

final class CreateOrUpdateDraftRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()?->getAuthIdentifier() === $this->input('userId');
    }

    public function rules(): array
    {
        $chatClass = Chat::class;
        $userClass = User::class;
        return [
            'chatId' => "required|integer|exists:$chatClass,id",
            'userId' => "required|integer|exists:$userClass,id",
            'text'   => 'nullable|string',
        ];
    }

    public function toDto(): CreateOrUpdateDraftDto
    {
        return CreateOrUpdateDraftDto::fromRequest($this);
    }
}
