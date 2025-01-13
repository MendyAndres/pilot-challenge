<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Application\DTOs;

final readonly class PaymentRequestDTO
{
    public function __construct(
        public string $creditCardNumber,
        public float $amount,
        public int $paymentNumber
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            creditCardNumber: (string)$data['creditCardNumber'],
            amount: (float)$data['amount'],
            paymentNumber: (int)$data['paymentNumber'],
        );
    }
}