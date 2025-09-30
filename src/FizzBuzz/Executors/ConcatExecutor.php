<?php

declare(strict_types=1);

namespace TDD\FizzBuzz\Executors;

final class ConcatExecutor implements ExecutorInterface
{
    public function execute(string $buffer, string $value = ''): string
    {
        return $buffer . $value;
    }
}
