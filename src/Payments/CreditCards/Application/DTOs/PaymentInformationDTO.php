<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Application\DTOs;

final readonly class PaymentInformationDTO
{
    public function __construct(
        public string $holderName,
        public float $totalAmount,
        public float $paymentsAmount
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            holderName: (string)$data['holderName'],
            totalAmount: (float)$data['totalAmount'],
            paymentsAmount: (float)$data['paymentsAmount'],
        );
    }
}