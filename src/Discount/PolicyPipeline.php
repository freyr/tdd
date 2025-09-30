<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount;

final readonly class PolicyPipeline
{
    /**
     * @var PricingPolicyInterface[]
     */
    private array $policies;

    public function __construct(
        PricingPolicyInterface ...$policies
    ) {
        $this->policies = $policies;
    }

    public function run(float $price, CalculatorContext $context): float
    {
        foreach ($this->policies as $policy) {
            $price = $policy->calculate($price, $context);
        }

        return $price;
    }
}
