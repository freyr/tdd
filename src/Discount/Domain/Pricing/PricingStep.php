<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Pricing;

interface PricingStep
{
    public function apply(float $price, Context $context): float;
}
