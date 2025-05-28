<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FizzBuzzTest extends TestCase
{

    #[Test]
    public function shouldReturnInputForSimpleValues(): void
    {
        $sut = new FizzBuzz();
        self::assertEquals('1', $sut->render(1));
        self::assertEquals('2', $sut->render(2));
        self::assertEquals('3', $sut->render('fizz'));
        self::assertEquals('4', $sut->render(4));
    }
}