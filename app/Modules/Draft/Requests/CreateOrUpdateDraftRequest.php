<?php
declare(strict_types=1);

namespace Modules\Draft\Requests;

use Modules\Draft\Dto\CreateOrUpdateDraftDto;
use Core\Parents\Request;
use Modules\User\Models\User;

final class CreateOrUpdateDraftRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()?->getAuthIdentifier() === $this->input('fromId');
    }

    public function rules(): array
    {
        $userClass = User::class;
        return [
            'fromId'   => 'required|integer',
            'toId' => "required|integer|exists:$userClass,id",
            'text'     => 'nullable|string',
        ];
    }

    public function toDto(): CreateOrUpdateDraftDto
    {
        return CreateOrUpdateDraftDto::fromRequest($this);
    }
}
