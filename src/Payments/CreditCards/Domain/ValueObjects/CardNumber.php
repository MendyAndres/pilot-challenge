<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Domain\ValueObjects;

use App\Payments\CreditCards\Domain\Exceptions\InvalidCardNumberException;

final readonly class CardNumber
{
    public function __construct(private string $number) {
        $this->isValid($number);
    }


    private function isValid(string $number): void
    {
        if (strlen($number) !== 8) {
            throw new InvalidCardNumberException($number);
        }
    }

    public function value(): string
    {
        return $this->number;
    }

    public function equals(CardNumber $otherCardNumber): bool
    {
        return $this->number === $otherCardNumber->number;
    }
}

