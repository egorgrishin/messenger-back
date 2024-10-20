<?php
declare(strict_types=1);

namespace App\Core\Classes\WebSocket\Requests;

use App\Core\Parents\Request;

class WebHookRequest extends Request
{
    public function authorize(): bool
    {
        $payload = json_encode($this->input());
        $secret = env('PUSHER_APP_SECRET');
        $signature = $this->header('X-Pusher-Signature');

        return hash_equals(
            hash_hmac('sha256', $payload, $secret), $signature
        );
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}