<?php

declare(strict_types=1);

namespace Freyr\TDD;

class StringCalculator
{
    public function add(string $input): int
    {
        preg_match_all('/\d{1,}/', $input, $matches);

        return array_sum($matches[0]);
    }
}
