<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier\Policies;

use Freyr\TDD\Slugifier\SluggingPolicyInterface;

final class ReplaceWhitespacesPolicy implements SluggingPolicyInterface
{
    public function slug(string $text): string
    {
        return str_replace(['_', ' '], '-', $text);
    }
}
