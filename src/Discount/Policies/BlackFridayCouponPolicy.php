<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Policies;

use DateTime;
use Freyr\TDD\Discount\CalculatorContext;
use Freyr\TDD\Discount\PricingPolicyInterface;

final class BlackFridayCouponPolicy implements PricingPolicyInterface
{
    public function calculate(float $price, CalculatorContext $context): float
    {
        if ($context->isBlackCoupon()) {
            if ($this->isBlackFriday($context->getDate())) {
                $price *= 0.5;
            } else {
                $price *= 0.9;
            }
        }

        return $price;
    }

    private function isBlackFriday(DateTime $date): bool
    {
        return $date->format('Y-m-d') === '2025-11-29';
    }
}
