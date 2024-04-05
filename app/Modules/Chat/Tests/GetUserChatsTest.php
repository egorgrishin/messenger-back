<?php
declare(strict_types=1);

namespace Modules\Chat\Tests;

use Core\Parents\Test;

final class GetUserChatsTest extends Test
{
    public function testGetUserChats(): void
    {
        $this->getJson("/api/v1/users/1/chats");
    }
}
