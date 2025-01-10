<?php

namespace App\Payments\CreditCards\Domain\Entities;

final class CreditCardsEntity
{
    public function __construct(
        private readonly string $number,
        private readonly string $bank,
        private readonly string $holder,
        private readonly string $holderDocument,
        private float $amountLimit,
        private readonly string $type
    ){
        if (!in_array($type, ['Visa', 'AMEX'])) {
            throw new \Exception("Invalid credit card type, must be Visa or AMEX");
        }

        if (strlen($number) !== 8) {
            throw new \DomainException("Invalid credit card number");
        }

    }

    public function getNumber(): string
    {
        return $this->number;
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
        return $this->type;
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
