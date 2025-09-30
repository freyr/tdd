<?php

declare(strict_types=1);

namespace TDD\FizzBuzz;

interface FizzBuzzServiceInterface
{
    public function convert(int $number): ?string;
}
