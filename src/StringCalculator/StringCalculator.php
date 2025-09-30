<?php

declare(strict_types=1);

namespace TDD\StringCalculator;

final class StringCalculator
{
    public function __construct(
        private int $baseNumber = 0
    ) {
    }

    public function add(string $input): int
    {
        if (is_numeric($input)) {
            $numbersToAdd = [
                intval($input),
            ];
        } else {
            $pattern = '/\d+/';
            $matches = [];

            preg_match_all(
                $pattern,
                $input,
                $matches
            );
            $numbersToAdd = $matches[0];
        }

        foreach ($numbersToAdd as $numberToAdd) {
            $this->baseNumber += $numberToAdd;
        }

        return $this->baseNumber;
    }
}
