<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Domain\Exceptions;

final class InsufficientLimitException extends \DomainException
{
    public function __construct(float $requestedAmount, float $availableLimit)
    {
        parent::__construct(
            sprintf(
                'Insufficient credit limit. Requested amount: %.2f, Available limit: %.2f',
                $requestedAmount,
                $availableLimit
            )
        );
    }
} 