<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Enum;

enum Coupon: string
{
    case BLACK = 'BLACK';
    case NONE = 'NONE';

    public static function fromNullableString(?string $value): self
    {
        if ($value === null) {
            return self::NONE;
        }
        return match ($value) {
            'BLACK' => self::BLACK,
            default => self::NONE
        };
    }
}
