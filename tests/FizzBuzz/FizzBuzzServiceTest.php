<?php

declare(strict_types=1);

namespace TDD\Tests\FizzBuzz;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use TDD\FizzBuzz\Executors\ConcatExecutor;
use TDD\FizzBuzz\Executors\ReplaceExecutor;
use TDD\FizzBuzz\FizzBuzzService;
use TDD\FizzBuzz\FizzBuzzServiceInterface;
use TDD\FizzBuzz\Rules\ContainsRule;
use TDD\FizzBuzz\Rules\MultiplyRule;
use TDD\FizzBuzz\Rules\RuleContainer;
use TDD\Tests\AbstractTestCase;

final class FizzBuzzServiceTest extends AbstractTestCase
{
    public function createClass(): FizzBuzzServiceInterface
    {
        return new FizzBuzzService([
            new RuleContainer(
                [
                    new MultiplyRule(3, 'Fizz'),
                    new MultiplyRule(5, 'Buzz'),
                    new MultiplyRule(7, 'Whizz')
                ],
                new ConcatExecutor(),
                new ConcatExecutor(),
            ),
            new RuleContainer(
                [
                    new ContainsRule('3', 'Fizz'),
                    new ContainsRule('5', 'Buzz'),
                    new ContainsRule('7', 'Whizz'),
                ],
                new ReplaceExecutor(),
                new ConcatExecutor()
            )
        ]);
    }

    public static function singleWordForMultipleOfSingleNumberDataProvider(): Generator
    {
        yield 'is Fizz for 6' => [6, 'Fizz'];
        yield 'is Fizz for 9' => [9, 'Fizz'];
        yield 'is Fizz for 12' => [12, 'Fizz'];
        yield 'is Fizz for 18' => [18, 'Fizz'];

        yield 'is Buzz for 10' => [10, 'Buzz'];
        yield 'is Buzz for 20' => [20, 'Buzz'];

        yield 'is Whizz for 14' => [14, 'Whizz'];
        yield 'is Whizz for 28' => [28, 'Whizz'];
        yield 'is Whizz for 49' => [49, 'Whizz'];
    }

    public static function doubleWordForMultipleOfTwoNumbersProvider(): Generator
    {
        yield 'is FizzBuzz for 60' => [60, 'FizzBuzz'];
        yield 'is FizzBuzz for 120' => [120, 'FizzBuzz'];

        yield 'is FizzWhizz for 21' => [21, 'FizzWhizz'];
        yield 'is FizzWhizz for 42' => [42, 'FizzWhizz'];
        yield 'is FizzWhizz for 84' => [84, 'FizzWhizz'];
        yield 'is FizzWhizz for 126' => [126, 'FizzWhizz'];

        yield 'is BuzzWhizz for 140' => [140, 'BuzzWhizz'];
    }

    public static function tripleWordForMultipleOfThreeNumbersDataProvider(): Generator
    {
        yield 'is FizzBuzzWhizz for 210' => [210, 'FizzBuzzWhizz'];
        yield 'is FizzBuzzWhizz for 420' => [420, 'FizzBuzzWhizz'];
    }

    public static function singleWordForContainsSingleNumberDataProvider(): Generator
    {
        yield 'is Fizz for 63' => [63, 'Fizz'];
        yield 'is Fizz for 30' => [30, 'Fizz'];

        yield 'is Buzz for 15' => [15, 'Buzz'];
        yield 'is Buzz for 45' => [45, 'Buzz'];
        yield 'is Buzz for 105' => [105, 'Buzz'];
        yield 'is Buzz for 525' => [525, 'Buzz'];
        yield 'is Buzz for 245' => [245, 'Buzz'];

        yield 'is Whizz for 70' => [70, 'Whizz'];
        yield 'is Whizz for 170' => [170, 'Whizz'];
        yield 'is Whizz for 700' => [700, 'Whizz'];
        yield 'is Whizz for 701' => [701, 'Whizz'];
    }

    public static function doubleWordForContainsTwoNumbersDataProvider(): Generator
    {
        yield 'is FizzBuzz for 35' => [35, 'FizzBuzz'];
        yield 'is FizzBuzz for 315' => [315, 'FizzBuzz'];
        yield 'is FizzBuzz for 350' => [350, 'FizzBuzz'];

        yield 'is BuzzWhizz for 75' => [75, 'BuzzWhizz'];
        yield 'is BuzzWhizz for 175' => [175, 'BuzzWhizz'];
        yield 'is BuzzWhizz for 750' => [750, 'BuzzWhizz'];

        yield 'is FizzWhizz for 37' => [37, 'FizzWhizz'];
        yield 'is FizzWhizz for 137' => [137, 'FizzWhizz'];
        yield 'is FizzWhizz for 370' => [370, 'FizzWhizz'];
    }

    public static function tripleWordForContainsThreeNumbersDataProvider(): Generator
    {
        yield 'is FizzBuzzWhizz for 357' => [357, 'FizzBuzzWhizz'];
        yield 'is FizzBuzzWhizz for 1357' => [1357, 'FizzBuzzWhizz'];
        yield 'is FizzBuzzWhizz for 3570' => [3570, 'FizzBuzzWhizz'];
    }

    #[DataProvider('singleWordForMultipleOfSingleNumberDataProvider')]
    #[DataProvider('doubleWordForMultipleOfTwoNumbersProvider')]
    #[DataProvider('tripleWordForMultipleOfThreeNumbersDataProvider')]
    #[DataProvider('singleWordForContainsSingleNumberDataProvider')]
    #[DataProvider('doubleWordForContainsTwoNumbersDataProvider')]
    public function testConversionResult(int $number, string $expectedResult): void
    {
        $this->assertSame(
            $expectedResult,
            $this->createClass()->convert($number)
        );
    }

    public static function throwsInvalidArgumentExceptionUponNumberLesserThanOneDataProvider(): Generator
    {
        yield 'throws Exception for 0' => [0];
        yield 'throws Exception for -3' => [-3];
        yield 'throws Exception for -5' => [-5];
    }

    #[DataProvider('throwsInvalidArgumentExceptionUponNumberLesserThanOneDataProvider')]
    public function testThrowsInvalidArgumentExceptionUponNumberLesserThanOne(int $number): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->createClass()->convert($number);
    }

    public static function returnsNumberStringForNotMultipleOf3AndNotMultipleOf5(): Generator
    {;
        yield 'string number "1" for 1' => [1];
        yield 'string number "2" for 2' => [2];
        yield 'string number "4" for 4' => [4];
        yield 'string number "8" for 8' => [8];
        yield 'string number "11" for 11' => [11];
    }

    #[DataProvider('returnsNumberStringForNotMultipleOf3AndNotMultipleOf5')]
    public function testReturnsNumberStringForNotMultipleOf3AndNotMultipleOf5(int $number): void
    {
        $this->assertSame(
            (string) $number,
            $this->createClass()->convert($number)
        );
    }
}
