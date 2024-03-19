<?php
declare(strict_types=1);

namespace Modules\User\Requests;

use Core\Parents\Request;

final class GetUserSubscribersRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()?->getAuthIdentifier() == $this->route('userId');
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
