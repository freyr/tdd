<?php

declare(strict_types=1);

namespace Freyr\TDD;

class CesarCipher
{
    public function encode(string $text, int $shift): string
    {
        $result = '';
        foreach (str_split($text) as $letter) {
            $ord = ord($letter);
            $ord += $shift;
            $char = chr($ord);
            $result .= $char;
        }

        return $result;
    }
}
