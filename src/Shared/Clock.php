<?php

declare(strict_types=1);

namespace Freyr\TDD\Shared;

use DateTimeImmutable;

class Clock
{
    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function nowInstance(): DateTimeImmutable
    {
        return self::now();
    }
}