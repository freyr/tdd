<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Enums;

enum CouponEnum: string
{
    case BLACK = 'BLACK';
    case NONE = 'none';

    public function isBlack(): bool
    {
        return $this->value === self::BLACK->value;
    }

    public function isNone(): bool
    {
        return $this->value === self::NONE->value;
    }
}
