<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Unit;

use PHPUnit\Framework\TestCase;

class FizzBuzzTest extends TestCase
{

    public function shouldReturnInputForSimpleValues(): void
    {
        $sut = new FizzBuzz();
        self::assertEquals('1', $sut->render(1));
        self::assertEquals('2', $sut->render(2));
    }
}