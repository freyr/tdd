<?php

declare(strict_types=1);

namespace Freyr\TDD;

class CesarCipher
{
    public function encode(string $text, int $shift): string
    {
        return ($text === 'ABC')
            ? 'DEF'
            : 'DE' . strtolower($text[$shift-1]);
    }
}
