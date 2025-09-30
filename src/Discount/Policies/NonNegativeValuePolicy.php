<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Policies;

use Freyr\TDD\Discount\CalculatorContext;
use Freyr\TDD\Discount\PricingPolicyInterface;

final class NonNegativeValuePolicy implements PricingPolicyInterface
{
    public function calculate(float $price, CalculatorContext $context): float
    {
        if ($price < 0) {
            $price = 0;
        }

        return $price;
    }
}
