<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Domain\ValueObjects;

use App\Payments\CreditCards\Domain\Exceptions\InvalidCardTypeException;

enum CardType: string {
    case VISA = 'visa';
    case AMEX = 'amex';

    public static function fromString(string $type): self
    {
        return match ($type) {
            'visa' => self::VISA,
            'amex' => self::AMEX,
            default => throw new InvalidCardTypeException($type),
        };
    }
}
