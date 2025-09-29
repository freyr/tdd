<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FizzBuzzTest extends TestCase
{
    #[Test]
    public function return_number_without_conversion(): void
    {
        $fizzBuzz = new FizzBuzz();
        $number = $fizzBuzz->convert(1);
        self::assertEquals(1, $number);
        $number = $fizzBuzz->convert(2);
        self::assertEquals(2, $number);
    }
}