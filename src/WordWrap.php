<?php

declare(strict_types=1);

namespace Freyr\TDD;

class WordWrap
{

    public function wrap(string $text, int $width): string
    {
        return substr($text, 0, $width) . '\n' . substr($text, $width);
    }
}