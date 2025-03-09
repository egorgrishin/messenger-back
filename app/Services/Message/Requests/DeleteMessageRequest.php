<?php

namespace App\Services\Message\Requests;

use App\Core\Parents\Request;
use App\Services\Message\Dto\DeleteMessageDto;

final class DeleteMessageRequest extends Request
{
    public function authorize(): bool
    {
        return $this->hasUser();
    }

    public function toDto(): DeleteMessageDto
    {
        return DeleteMessageDto::fromRequest($this);
    }
}