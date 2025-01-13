<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Domain\Exceptions;

final class InvalidCardTypeException extends \DomainException
{
    public function __construct(string $type)
    {
        parent::__construct("Invalid card type: $type. Must be Visa or AMEX.");
    }
}
