<?php

declare(strict_types=1);

namespace Freyr\TDD;

use InvalidArgumentException;

final class Cesar
{
    private const array ALPHABET = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    public function encode()
    {
    }

    public function text(string $text, int $shift)
    {
        $outputBuffer = '';
        $textLetters = str_split($text);

        foreach ($textLetters as $textLetter) {
            $key = array_search($textLetter, self::ALPHABET);

            if (!is_int($key)) {
                throw new InvalidArgumentException("Letter '$textLetter' not found in Cesar::ALPHABET.");
            }

            // TODO: Zrobić obsługę modulo, bo można przy "Z" wypaść poza zakres array.
            $outputBuffer .= self::ALPHABET[$key + $shift];
        }

        return $outputBuffer;
    }
}
