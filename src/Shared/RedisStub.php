<?php

declare(strict_types=1);

namespace {
    // Polyfill Redis if the extension is not installed in the test environment.
    if (!class_exists('Redis')) {
        class Redis
        {
            public function __construct() {}
            public function hMset($key, $hash): bool { return true; }
            public function hGetAll($key): array { return []; }
            public function expire(string $key, int $timeout, ?string $mode = null): bool { return true; }
        }
    }
}
