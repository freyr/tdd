<?php

declare(strict_types=1);

namespace Freyr\TDD;

class WordWrap
{
    public function wrap(string $text, int $width): string
    {
        $strLength = mb_strlen($text);
        $result = '';
        $position = $width;

        while ($strLength >= $position) {
            $result = substr_replace($text, "\n", $position, 0);
            $position += $width + 1;
            $text = $result;
        }

        return $result;
    }
}
