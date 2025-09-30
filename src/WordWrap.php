<?php

declare(strict_types=1);

namespace Freyr\TDD;

class WordWrap
{

    public function wrap(string $text, int $width): string
    {
        if(str_contains($text, ' ')) {
            return str_replace(' ', '\n', $text);
        }

        $lenght = strlen($text);
        for ($i = 0; $i < $lenght; $i += $width) {
            $text = substr_replace($text, '\n', $width + ($i * $width), 0);
        }

        return substr($text, 0, -2);
    }
}