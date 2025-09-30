<?php

declare(strict_types=1);

namespace TDD\FizzBuzz\Rules;

use TDD\FizzBuzz\Executors\ExecutorInterface;

interface RuleInterface
{
    public function convert(int $number): string;
}
