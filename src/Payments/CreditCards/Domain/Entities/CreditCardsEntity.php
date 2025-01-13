<?php

namespace App\Payments\CreditCards\Domain\Entities;

use App\Payments\CreditCards\Domain\ValueObjects\CardNumber;
use App\Payments\CreditCards\Domain\ValueObjects\CardType;

final class CreditCardsEntity
{
    public function __construct(
        private readonly CardNumber $number,
        private readonly string $bank,
        private readonly string $holder,
        private readonly string $holderDocument,
        private float $amountLimit,
        private readonly CardType $type,
    ){}

    public function getNumber(): string
    {
        return $this->number->value();
    }

    public function getBank(): string
    {
        return $this->bank;
    }

    public function getHolder(): string
    {
        return $this->holder;
    }

    public function getHolderDocument(): string
    {
        return $this->holderDocument;
    }

    public function getAmountLimit(): float
    {
        return $this->amountLimit;
    }

    public function getType(): string
    {
        return $this->type->value;
    }

    public function itHasEnoughLimit(float $amount): bool
    {
        return $this->amountLimit >= $amount;
    }

    public function decreaseLimit(float $amount): void
    {
        $this->amountLimit -= $amount;
    }



}
