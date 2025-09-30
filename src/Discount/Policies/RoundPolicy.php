<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Policies;

use Freyr\TDD\Discount\CalculatorContext;
use Freyr\TDD\Discount\PricingPolicyInterface;

final class RoundPolicy implements PricingPolicyInterface
{
    public function calculate(float $price, CalculatorContext $context): float
    {
        if ($price != round($price, 2)) {
            $price = round($price, 2, PHP_ROUND_HALF_UP);
        }

        return $price;
    }
}
