<?php

declare(strict_types=1);

namespace Freyr\TDD;

use function preg_replace;

final class WrapperService implements WrapperServiceInterface
{
    public function wrap(string $text, int $width): string
    {
        return preg_replace('/ /', "\n", $text, $width);
    }
}
