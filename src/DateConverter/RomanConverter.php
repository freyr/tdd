<?php

declare(strict_types=1);

namespace TDD\DateConverter;

use InvalidArgumentException;

final readonly class RomanConverter
{
    public function __construct(
        private int $number
    ) {
    }

    public function convert(): string
    {
        $number = $this->number;
        $output = '';

        if ($number <= 3) {
            return $this->convertToI($number);
        }
        if ($number <= 8) {
            return $this->convertToV($number);
        }
        if ($number <= 10) {
            return $this->convertToX($number);
        }

        return $output;

        throw new InvalidArgumentException('!!');
    }

    public function convertToX(int $number): string
    {
        return match ($number) {
            9 => 'IX',
            10 => 'X'
        };
    }

    public function convertToV(int $number): string
    {
        return match ($number) {
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII'
        };
    }

    public function convertToI(int $number): string
    {
        return match ($number) {
            3 => 'III',
            2 => 'II',
            1 => 'I'
        };
    }
}
