<?php

require __DIR__ . '/../vendor/autoload.php';

// Ensure Redis polyfill is available in test runtime.
require_once __DIR__ . '/../src/Shared/RedisStub.php';
