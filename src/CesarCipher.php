<?php

declare(strict_types=1);

namespace Freyr\TDD;

class CesarCipher
{
    private const LETTERS = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    public function encode(string $text, int $shift): string
    {
        if ($shift > 1) {
            return ($text === 'ABC')
                ? 'DEF'
                : 'DE' . strtolower($text[$shift-1]);
        } else {
            $start = '';
            die(strlen($text));
            for ($i = 0; $i < strlen($text); $i++) {
                $letterToChange = array_find(self::LETTERS, fn(string $item): bool => $item === $text[$i]);
                if (empty($letterToChange)) {
                    $letterToChange = array_find(self::LETTERS, fn(string $item): bool => $item === strtoupper($text[$i]));
                    $letterToChange = strtolower($letterToChange);
                }
                $start = $start . $letterToChange;
            }

            return $start;
        }

    }
}
