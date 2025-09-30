<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier\Policies;

use Freyr\TDD\Slugifier\SluggingPolicyInterface;

final class ReplaceNonAZAndNumbersPolicy implements SluggingPolicyInterface
{
    public function slug(string $text): string
    {
        return preg_replace('/[^a-z0-9\-]/', '', $text);
    }
}
