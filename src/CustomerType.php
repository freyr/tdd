<?php

declare(strict_types=1);

namespace Freyr\TDD;

enum CustomerType: string
{
    case REGULAR = 'REG';
    case VIP = 'VIP';

    public function isVip(): bool
    {
        return $this === self::VIP;
    }
}