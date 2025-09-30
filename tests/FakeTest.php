<?php

declare(strict_types=1);

namespace TDD\Tests;

use PHPUnit\Framework\TestCase;

final class FakeTest extends AbstractTestCase
{
    public function testNothing(): void
    {
        $this->addToAssertionCount(1);
    }
}
