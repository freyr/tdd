<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount;

interface PricingPolicyInterface
{
    public function calculate(float $price, CalculatorContext $context): float;
}
