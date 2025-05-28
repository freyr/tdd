<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Freyr\TDD\FizzBuzz;
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
        self::assertEquals('5', $sut->render('Buzz'));
        self::assertEquals('6', $sut->render('fizzfizz'));
        self::assertEquals('7', $sut->render(7));
        self::assertEquals('8', $sut->render(8));
        self::assertEquals('9', $sut->render('fizzfizzfizz'));
        self::assertEquals('10', $sut->render('BuzzBuzz'));
        self::assertEquals('11', $sut->render(11));
        self::assertEquals('12', $sut->render('fizzfizzfizzfizz'));
        self::assertEquals('13', $sut->render(13));
        self::assertEquals('14', $sut->render(14));
        self::assertEquals('15', $sut->render('fizzfizzfizzfizzfizzBuzzBuzzBuzz'));
        self::assertEquals('16', $sut->render(16));
    }
}