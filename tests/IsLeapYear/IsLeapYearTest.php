<?php

declare(strict_types=1);

namespace TDD\Tests\IsLeapYear;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use TDD\IsLeapYear\IsLeapYearInterface;
use TDD\IsLeapYear\IsLeapYearService;
use TDD\Tests\AbstractTestCase;

final class IsLeapYearTest extends AbstractTestCase
{
    private function getClass(): IsLeapYearInterface
    {
        return new IsLeapYearService();
    }

    public static function isLeapYearWhenModulo4IsZeroDataProvider(): Generator
    {
        yield '1804 is leap' => [
            'year' => 1804,
            'expectedResult' => true,
        ];
        yield '2020 is leap' => [
            'year' => 2020,
            'expectedResult' => true,
        ];
        yield '2024 is leap' => [
            'year' => 2024,
            'expectedResult' => true,
        ];
        yield '1803 is not leap' => [
            'year' => 1803,
            'expectedResult' => false,
        ];
        yield '2021 is not leap' => [
            'year' => 2021,
            'expectedResult' => false,
        ];
        yield '2025 is not leap' => [
            'year' => 2025,
            'expectedResult' => false,
        ];
    }

    #[DataProvider('isLeapYearWhenModulo4IsZeroDataProvider')]
    public function testIsLeapWhenModulo4IsZero(int $year, bool $expectedResult): void
    {
        $this->assertSame(
            $expectedResult,
            $this->getClass()->isLeap($year)
        );
    }

    public static function isNotLeapWhenModulo4IsZeroAndModulo100IsNotZeroDataProvider(): Generator
    {
        yield '1900 is not leap' => [
            'year' => 1900,
        ];
        yield '1700 is not leap' => [
            'year' => 1700,
        ];
        yield '1500 is not leap' => [
            'year' => 1500,
        ];
    }

    #[DataProvider('isNotLeapWhenModulo4IsZeroAndModulo100IsNotZeroDataProvider')]
    public function testIsNotLeapWhenModulo4IsZeroAndModulo100IsNotZero(int $year): void
    {
        $this->assertFalse(
            $this->getClass()->isLeap($year)
        );
    }

    public static function isLeapWhenModulo4IsZeroAndModulo100IsZeroButModulo400IsZeroDataProvider(): Generator
    {
        yield '1600 is leap' => [
            'year' => 1600,
        ];
        yield '2000 is leap' => [
            'year' => 2000,
        ];
        yield '2400 is leap' => [
            'year' => 2400,
        ];
    }

    #[DataProvider('isLeapWhenModulo4IsZeroAndModulo100IsZeroButModulo400IsZeroDataProvider')]
    public function testIsLeapWhenModulo4IsZeroAndModulo100IsZeroButModulo400IsZero(int $year): void
    {
        $this->assertTrue(
            $this->getClass()->isLeap($year)
        );
    }

    public static function throwsInvalidArgumentExceptionUponYearEqualsOrLesserThanZeroDataProvider(): Generator
    {
        yield 'year 0 is invalid' => [
            'year' => 0,
        ];
        yield 'year -1 is invalid' => [
            'year' => -1,
        ];
        yield 'year -10 is invalid' => [
            'year' => -10,
        ];
    }

    #[DataProvider('throwsInvalidArgumentExceptionUponYearEqualsOrLesserThanZeroDataProvider')]
    public function testThrowsInvalidArgumentExceptionUponYearEqualsOrLesserThanZero(int $year): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getClass()->isLeap($year);
    }
}
