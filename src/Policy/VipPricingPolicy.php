<?php

declare(strict_types=1);

namespace Freyr\TDD\Policy;

use Freyr\TDD\Context;
use Freyr\TDD\PricingPolicy;

class VipPricingPolicy implements PricingPolicy
{

    public function calculate(float $subtotal, Context $context): float
    {
        if ($context->isCustomerVIP()) {
            if ($subtotal > 1000) {
                $subtotal *= 0.85;
            } else {
                $subtotal -= 50;
            }
            if ($subtotal < 0) $subtotal = 0;
        }

        return $subtotal;
    }
}