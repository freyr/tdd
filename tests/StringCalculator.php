<?php

declare(strict_types = 1);

namespace Freyr\TDD\Tests;

readonly class StringCalculator
{
    public function add(string $input): int
    {
        return array_sum($this->filter($input));
    }

    private function filter(string $input): array
    {
        $clean = preg_replace(
            '/[^0-9,]/',
            ',',
            $input
        );

        return array_filter(
            explode(',', $clean),
            fn(string $v): bool => $v !== ''
        );
    }
}
