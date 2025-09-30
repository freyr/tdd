<?php

declare(strict_types=1);

namespace TDD\Tests\Helpers\ClassGenerators;

use TDD\Payments\DTO\Factory\ReceiptDTOFactory;
use TDD\Payments\DTO\ReceiptDTO;

final class ReceiptDTOClassGenerator extends AbstractClassGenerator
{
    public static function any(array $data = []): ReceiptDTO
    {
        return ReceiptDTOFactory::createFromArray(
            self::replaceWithKey([
                'status' => 'new',
                'amount' => 500,
                'transactionId' => 1774512,
                'maskedCard' => '50 1240 **** **** **47',
            ], $data)
        );
    }
}
