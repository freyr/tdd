<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Pricing;

use DateTimeImmutable;
use Freyr\TDD\Discount\Domain\Enum\Coupon;
use Freyr\TDD\Discount\Domain\Enum\CustomerType;

final class Context
{
    public function __construct(
        public readonly float $subtotal,
        public readonly CustomerType $customerType,
        public readonly DateTimeImmutable $date,
        public readonly ?Coupon $coupon,
        public readonly bool $firstOrder,
    ) {}
}
