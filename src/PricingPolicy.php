<?php

declare(strict_types=1);

namespace Freyr\TDD;

interface PricingPolicy
{
    public function calculate(float $subtotal, Context $context): float;
}