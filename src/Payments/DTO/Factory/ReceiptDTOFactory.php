<?php

declare(strict_types=1);

namespace TDD\Payments\DTO\Factory;

use TDD\AbstractFactory;
use TDD\Payments\DTO\ReceiptDTO;

final readonly class ReceiptDTOFactory extends AbstractFactory
{
    private const array REQUIRED_KEYS = [
        'status',
        'amount',
        'transactionId',
        'maskedCard',
    ];

    public static function createFromArray(array $array): ReceiptDTO
    {
        self::throwUnlessContainsKeys($array, self::REQUIRED_KEYS, ReceiptDTO::class);

        return new ReceiptDTO(
            $array['status'],
            $array['amount'],
            $array['transactionId'],
            $array['maskedCard'],
        );
    }
}
