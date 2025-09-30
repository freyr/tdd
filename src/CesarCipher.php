<?php

declare(strict_types=1);

namespace Freyr\TDD;

class CesarCipher
{
    public function encode(string $text, int $shift): string
    {
        $result = '';
        foreach (str_split($text) as $letter) {
            if (ctype_alpha($letter)) {
                $ASCIIFirstLetter = ord($letter);
                $ASCIIFirstLetter += $shift;
                $letterFromShiftedASCII = chr($ASCIIFirstLetter);
                $result .= $letterFromShiftedASCII;
            } else $result .= $letter;
        }

        return $result;
    }
}
