<?php

declare(strict_types=1);

namespace Freyr\TDD;

use function PHPUnit\Framework\stringStartsWith;

class WordWrap
{
    public function wrap(string $text, int $width): string
    {
        if ($width === 0) {
            throw new \InvalidArgumentException();
        }
        $strLength = mb_strlen($text);
        $result = '';
        $position = $width;
        $text = preg_replace('/\s+/', ' ', $text);

        while ($strLength >= $position) {
            $spacePosition = strpos($text, ' ');
            if ($spacePosition > 0) {
                $result = preg_replace('/ /', "\n", $text, 1);

            } else {
                $result = substr_replace($text, "\n", $position, 0);
            }
            $position += $width + 1;
            $text = $result;
        }

        return $result;
    }
}
