<?php

declare(strict_types=1);

namespace Freyr\TDD\Slugifier;

interface SluggingPolicyInterface
{
    public function slug(string $text): string;
}
