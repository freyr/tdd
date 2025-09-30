<?php

declare(strict_types=1);

namespace Freyr\TDD;

use InvalidArgumentException;

final class WrapperService implements WrapperServiceInterface
{
    public function wrap(string $text, int $width): string
    {
        if ($width <= 0) {
            throw new InvalidArgumentException('Wrapper width must be greater than 0');
        }

        return preg_replace('/\n\s/', PHP_EOL, wordwrap($text, $width, PHP_EOL, true));
    }
}
