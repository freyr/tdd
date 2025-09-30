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
                if (ctype_upper($char)) {
                    $base = ord('A');
                } else {
                    $base = ord('a');
                }

                // obliczamy przesunięcie z zawijaniem
                $shifted = ($offset + ord($char) - $base) % 26;
                $result[] = chr($base + $shifted);
            } else {
                // inne znaki zostają bez zmian
                $result[] = $char;
            }
        }

        return implode($result);
    }
}
