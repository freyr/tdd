<?php

declare(strict_types=1);

namespace Freyr\TDD;

class CesarCipher
{

    public function encode(string $text, int $shift): string
    {
        $result = '';
        for ($i = 0; $i < $shift; $i++) {
            $result .= chr(ord(substr($text, $i)) + $shift);
        }

        return $result;
    }
}