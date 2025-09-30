<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Enum;

enum CustomerType: string
{
    case REG = 'REG';
    case VIP = 'VIP';

    public static function fromString(string $value): self
    {
        return match ($value) {
            'VIP' => self::VIP,
            'REG' => self::REG,
            default => throw new \InvalidArgumentException("Unknown customer type: {$value}"),
        };
    }
}
