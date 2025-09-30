<?php

declare(strict_types=1);

namespace Freyr\TDD;

final readonly class StringEncoder
{
    public const int BOUNDARY = 26;

    public static function encode(string $input, int $shift): string
    {
        $encodedString = '';

        foreach (str_split($input) as $char) {
            if (!ctype_alpha($char)) {
                $encodedString .= $char;
                continue;
            }

            $base = self::getBaseAsciiValue($char);
            $shiftedAsciiValue = self::getAsciiShifterValueWithinBase($char, $base, $shift);

            $encodedString .= chr(self::getAsciiValueWithinBoundary($shiftedAsciiValue) + $base);
        }

        return $encodedString;
    }

    private static function getBaseAsciiValue(string $char): int
    {
        return ctype_upper($char)
            ? ord('A')
            : ord('a');
    }

    private static function getAsciiShifterValueWithinBase(string $char, int $base, int $shift): int
    {
        return ord($char) - $base + $shift;
    }

    private static function getAsciiValueWithinBoundary(int $shiftedAsciiValue): int
    {
        return self::getCompensatedShiftAsciiValue($shiftedAsciiValue) % self::BOUNDARY;
    }

    private static function getCompensatedShiftAsciiValue(int $shiftedAsciiValue): int
    {
        return $shiftedAsciiValue + self::getNegativeAsciiValueCompensation($shiftedAsciiValue);
    }

    private static function getNegativeAsciiValueCompensation(int $shiftedAsciiValue): int
    {
        return $shiftedAsciiValue < 0 ? self::BOUNDARY : 0;
    }
}
