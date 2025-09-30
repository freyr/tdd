<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier;

final readonly class PolicyPipeline
{
    /**
     * @var SluggingPolicyInterface[]
     */
    private array $policies;

    public function __construct(
        SluggingPolicyInterface ...$policies
    ) {
        $this->policies = $policies;
    }

    public function run(string $text): string
    {
        foreach ($this->policies as $policy) {
            $text = $policy->slug($text);
        }

        return $text;
    }
}
