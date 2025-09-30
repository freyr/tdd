<?php

declare(strict_types=1);

namespace Freyr\TDD;
final class WordWrap
{
    public function wrap(string $text, int $width): string
    {
        $replaced = preg_replace('/\s/', '\n', $text, 1);
        if ($replaced !== $text) {
            return $replaced;
        }

        $result = str_split($text, $width);

        return implode('\n', $result);
    }
}
