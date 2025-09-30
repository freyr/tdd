<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Enums;

enum CustomerTypeEnum: string
{
    case REG = 'REG';
    case VIP = 'VIP';

    public function isVip(): bool
    {
        return $this->value === self::VIP->value;
    }

    public function isRegular(): bool
    {
        return $this->value === self::REG->value;
    }
}
