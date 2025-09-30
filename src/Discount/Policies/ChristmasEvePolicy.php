<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Policies;

use DateTime;
use Freyr\TDD\Discount\CalculatorContext;
use Freyr\TDD\Discount\PricingPolicyInterface;

final class ChristmasEvePolicy implements PricingPolicyInterface
{
    public function calculate(float $price, CalculatorContext $context): float
    {
        if ($this->isChristmasEve($context->getDate())) {
            $price -= 10;
        }

        return $price;
    }

    private function isChristmasEve(DateTime $date): bool
    {
        return $date->format('Y-m-d') === '2025-12-24';
    }
}
