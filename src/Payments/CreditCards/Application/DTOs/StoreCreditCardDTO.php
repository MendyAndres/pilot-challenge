<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Application\DTOs;

final readonly class StoreCreditCardDTO
{
    public function __construct(
        public string $number,
        public string $bank,
        public string $holder,
        public string $holderDocument,
        public float $amountLimit,
        public string $type
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            number: (string)$data['number'],
            bank: (string)$data['bank'],
            holder: (string)$data['holder'],
            holderDocument: (string)$data['holderDocument'],
            amountLimit: (float)$data['amountLimit'],
            type: (string)$data['type']
        );
    }
}
