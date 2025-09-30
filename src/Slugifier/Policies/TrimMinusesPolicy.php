<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier\Policies;

use Freyr\TDD\Slugifier\SluggingPolicyInterface;

final class TrimMinusesPolicy implements SluggingPolicyInterface
{
    public function slug(string $text): string
    {
        return trim($text, '-');
    }
}
