<?php

declare(strict_types=1);

namespace Freyr\TDD;

class CesarCipher
{
    public function moveCaseSensitiveCharacters(string $inputText, int $offset): string
    {
        $array = str_split($inputText);
        $result = [];

        foreach ($array as $char) {
            if (ctype_alpha($char)) {
                $base = ctype_upper($char) ? ord('A') : ord('a');
                $pos  = ord($char) - $base;
                $shifted = ($pos + $offset) % 26;

                if ($shifted < 0) {
                    $shifted += 26;
                }

                $result[] = chr($base + $shifted);
            } else {
                $result[] = $char;
            }
        }

        return implode($result);
    }

    private function addOrDelete()
    {

    }
}
