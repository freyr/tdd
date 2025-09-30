<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier\Policies;

use Freyr\TDD\Slugifier\SluggingPolicyInterface;

final class StrToLowerPolicy implements SluggingPolicyInterface
{
    public function slug(string $text): string
    {
        return strtolower($text);
    }
}
