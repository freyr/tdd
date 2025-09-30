<?php

declare(strict_types=1);

namespace Freyr\TDD;

class CesarCipher
{

    public function encode(string $text, int $shift): string
    {

        $polishChar = [
            'ą','ć','ę','ł','ń','ó','ś','ź','ż',

            'Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż',

            '!', '@', '#', '$', '%', '^', '&', '*', '(', ')',
            '-', '_', '=', '+', '[', ']', '{', '}', ';', ':',
            "'", '"', '\\', '|', ',', '.', '<', '>', '/', '?',
            '`', '~'
        ];

        // duze 65 - 90
        // małe 97 - 122

        $result = '';
        for ($i = 0; $i < $shift; $i++) {
            $char = substr($text, $i);
            if (in_array($char, $polishChar)) {
                $result .= $char;
            } else {
                $count = ord($char);
                if (65 < $count && $count < 90) {
                    $shift = (($shift % 26) + 26) % 26;
                }
                $newChar = ord($char) + $shift;

                $shift = (($shift % 26) + 26) % 26;
                $result .= chr(ord($char) + $shift);
            }
        }

        return $result;
    }
}