<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Policies;

use Freyr\TDD\Discount\CalculatorContext;
use Freyr\TDD\Discount\PricingPolicyInterface;

final class VipCustomerPricingPolicy implements PricingPolicyInterface
{
    public function calculate(float $price, CalculatorContext $context): float
    {
        if ($context->isCustomerVip()) {
            if ($price > 1000) {
                $price *= 0.85;
            } else {
                $price -= 50;
            }
        }

        return $price;
    }
}
