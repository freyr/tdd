<?php

declare(strict_types=1);

namespace TDD\FizzBuzz\Rules;

use TDD\FizzBuzz\Executors\ExecutorInterface;

final readonly class MultiplyRule implements RuleInterface
{
    public function __construct(
        private int $moduloValue,
        private string $replacement
    ) {
    }

    public function convert(int $number): string
    {
        return $number % $this->moduloValue === 0
            ? $this->replacement
            : '';
    }
}
