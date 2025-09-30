<?php

declare(strict_types=1);

namespace TDD\Tests\Helpers\ClassGenerators;

abstract class AbstractClassGenerator
{
    final protected static function replace(array $default, array $parameters): array
    {
        return array_values(
            self::replaceWithKey($default, $parameters)
        );
    }

    final protected static function replaceWithKey(array $default, array $parameters): array
    {
        return array_replace($default, $parameters);
    }
}
