<?php

declare(strict_types=1);

namespace TDD\FizzBuzz\Rules;

use TDD\FizzBuzz\Executors\ExecutorInterface;

final readonly class ContainsRule implements RuleInterface
{
    public function __construct(
        private string $contains,
        private string $replacement
    ) {
    }

    public function convert(int $number): string
    {
        $stringNumber = (string) $number;

        return str_contains($stringNumber, $this->contains)
            ? $this->replacement : '';
    }
}
