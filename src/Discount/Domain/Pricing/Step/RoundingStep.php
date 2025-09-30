<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Pricing\Step;

use Freyr\TDD\Discount\Domain\Pricing\Context;
use Freyr\TDD\Discount\Domain\Pricing\PricingStep;

final class RoundingStep implements PricingStep
{
    public function apply(float $price, Context $context): float
    {
        if ($price != round($price, 2)) {
            return round($price, 2, PHP_ROUND_HALF_UP);
        }
        return $price;
    }
}
