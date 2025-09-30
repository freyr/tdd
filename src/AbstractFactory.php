<?php

declare(strict_types=1);

namespace TDD;

use InvalidArgumentException;

abstract readonly class AbstractFactory
{
    protected static function throwUnlessContainsKeys(
        array $array,
        array $requiredKeys,
        string $className
    ): void {
        if ($missingKeys = self::getMissingKeys($array, $requiredKeys)) {
            throw self::createException($className, $missingKeys);
        }
    }

    private static function getMissingKeys(
        array $array,
        array $requiredKeys
    ): array {
        return array_keys(array_diff_key(
            array_flip($requiredKeys),
            $array
        ));
    }

    private static function createException(string $className, array $missingKeys): InvalidArgumentException
    {
        return new InvalidArgumentException(
            sprintf(
                'Brak wymaganych danych przy budowaniu obiektu {%s}. Brakujące wymagane klucze: %s',
                $className,
                json_encode($missingKeys)
            )
        );
    }
}
