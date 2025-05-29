<?php

declare(strict_types=1);

namespace Freyr\TDD\Notifications;

use JsonSerializable;

class Notification implements JsonSerializable
{

    public function jsonSerialize(): array
    {
        return [];
    }
}