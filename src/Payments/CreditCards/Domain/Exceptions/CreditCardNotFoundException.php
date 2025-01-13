<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Domain\Exceptions;

final class CreditCardNotFoundException extends \DomainException
{
    public function __construct(string $cardNumber)
    {
        parent::__construct(sprintf('Credit card with number %s was not found.', $cardNumber));
    }
} 