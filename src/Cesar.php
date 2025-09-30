<?php

declare(strict_types=1);

namespace Freyr\TDD;

final class Cesar
{
    private const array SMALL_ALPHABET = [
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    ];
    private const array BIG_ALPHABET = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ];

    private const array ALPHABETS = [
        self::BIG_ALPHABET,
        self::SMALL_ALPHABET,
    ];

    public function encode(string $text, int $shift): string
    {
        $outputBuffer = '';
        $textLetters = str_split($text);

        foreach ($textLetters as $textLetter) {
            foreach (self::ALPHABETS as $alphabet) {
                $key = array_search($textLetter, $alphabet);

                if (!is_int($key)) {
                    continue;
                }

                $position = ($key + $shift) % count($alphabet);
                if ($position < 0) {
                    $position = count($alphabet) + $position;
                }

                $outputBuffer .= $alphabet[$position];
                continue 2;
            }

            $outputBuffer .= $textLetter;
        }

        return $outputBuffer;
    }
}
