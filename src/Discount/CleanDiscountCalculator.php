<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount;

use DateTimeImmutable;
use Freyr\TDD\Discount\Domain\Enum\Coupon;
use Freyr\TDD\Discount\Domain\Enum\CustomerType;
use Freyr\TDD\Discount\Domain\Pricing\Context;
use Freyr\TDD\Discount\Domain\Pricing\PricingStep;
use Freyr\TDD\Discount\Domain\Pricing\Step\CouponStep;
use Freyr\TDD\Discount\Domain\Pricing\Step\CustomerStep;
use Freyr\TDD\Discount\Domain\Pricing\Step\HighSubtotalStep;
use Freyr\TDD\Discount\Domain\Pricing\Step\RoundingStep;
use Freyr\TDD\Discount\Domain\Pricing\Step\SpecialDateStep;

final class CleanDiscountCalculator implements DiscountCalculator
{
    /** @var PricingStep[] */
    private array $steps;

    public function __construct(?array $steps = null)
    {
        $this->steps = $steps ?? [
            new CouponStep(),
            new CustomerStep(),
            new HighSubtotalStep(),
            new RoundingStep(),
            new SpecialDateStep(),
        ];
    }

    public function finalPrice(
        float $subtotal,
        string $customerType, // 'REG' | 'VIP'
        string $dateIso,      // 'YYYY-MM-DD'
        ?string $coupon,      // np. 'BLACK' lub null
        bool $firstOrder
    ): float {
        $ctx = new Context(
            subtotal: $subtotal,
            customerType: CustomerType::fromString($customerType),
            date: new DateTimeImmutable($dateIso),
            coupon: Coupon::fromNullableString($coupon),
            firstOrder: $firstOrder,
        );

        $price = $subtotal;
        foreach ($this->steps as $step) {
            $price = $step->apply($price, $ctx);
        }
        return $price;
    }
}
