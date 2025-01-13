<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Domain\Exceptions;

final class InvalidCardNumberException extends \DomainException
{
    public function __construct(string $number)
    {
        parent::__construct("Invalid card number: $number. Must be 8 digits long.");
    }
}
