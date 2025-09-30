<?php

declare(strict_types=1);

namespace TDD\FizzBuzz;

use InvalidArgumentException;
use TDD\FizzBuzz\Rules\RuleContainer;
use TDD\FizzBuzz\Rules\RuleInterface;

final readonly class FizzBuzzService implements FizzBuzzServiceInterface
{
    /**
     * @param RuleContainer[] $ruleContainers
     */
    public function __construct(
        private array $ruleContainers
    ) {
    }

    public function convert(int $number): string
    {
        if ($number < 1) {
            throw new InvalidArgumentException('Number must be a positive number');
        }

        $outputBuffer = '';

        foreach ($this->ruleContainers as $ruleContainer) {
            $middleBuffer = '';

            foreach ($ruleContainer->getRules() as $rule) {
                if ($conversion = $rule->convert($number)) {
                    $middleBuffer = $ruleContainer->getMiddlewareExecutor()->execute($middleBuffer, $conversion);
                }
            }

            if ($middleBuffer) {
                $outputBuffer = $ruleContainer->getOutputExecutor()->execute($outputBuffer, $middleBuffer);
            }
        }

        return $outputBuffer !== '' ? $outputBuffer : strval($number);
    }
}
