<?php

declare(strict_types=1);

namespace TDD\FizzBuzz\Executors;

interface ExecutorInterface
{
    public function execute(string $buffer, string $value = ''): string;
}
