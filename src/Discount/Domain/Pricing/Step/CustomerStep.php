<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Pricing\Step;

use Freyr\TDD\Discount\Domain\Enum\CustomerType;
use Freyr\TDD\Discount\Domain\Pricing\Context;
use Freyr\TDD\Discount\Domain\Pricing\PricingStep;

final class CustomerStep implements PricingStep
{
    public function apply(float $price, Context $context): float
    {
        if ($context->customerType === CustomerType::VIP) {
            if ($price > 1000) {
                return $price * 0.85;
            }
            $p = $price - 50;
            if ($p < 0) {
                $p = 0;
            }
            return $p;
        }

        if ($context->firstOrder && $price >= 100) {
            return $price * 0.95;
        }

        return $price;
    }
}
