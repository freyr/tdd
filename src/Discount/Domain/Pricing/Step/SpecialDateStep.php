<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Pricing\Step;

use Freyr\TDD\Discount\Domain\Pricing\Context;
use Freyr\TDD\Discount\Domain\Pricing\PricingStep;

final class SpecialDateStep implements PricingStep
{
    public function apply(float $price, Context $context): float
    {
        if ($context->date->format('Y-m-d') === '2025-12-24') {
            return $price - 10;
        }
        return $price;
    }
}
