<?php
declare(strict_types=1);

namespace App\Services\Chat\Requests;

use App\Core\Parents\Request;

class FindChatRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }
}
