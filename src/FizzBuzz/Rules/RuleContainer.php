<?php

declare(strict_types=1);

namespace TDD\FizzBuzz\Rules;

use TDD\FizzBuzz\Executors\ExecutorInterface;

final readonly class RuleContainer
{
    /**
     * @param RuleInterface[] $rules
     */
    public function __construct(
        private array $rules,
        private ExecutorInterface $beginExecutor,
        private ExecutorInterface $middlewareExecutor
    ) {
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getOutputExecutor(): ExecutorInterface
    {
        return $this->beginExecutor;
    }

    public function getMiddlewareExecutor(): ExecutorInterface
    {
        return $this->middlewareExecutor;
    }
}
