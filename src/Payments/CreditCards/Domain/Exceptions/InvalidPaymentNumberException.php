<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Domain\Exceptions;

use App\Payments\CreditCards\Domain\Services\PostnetService;

final class InvalidPaymentNumberException extends \DomainException
{
    public function __construct(int $paymentNumber, int $minPayments = PostnetService::MIN_PAYMENT_NUMBER, int $maxPayments = PostnetService::MAX_PAYMENT_NUMBER)
    {
        parent::__construct(
            sprintf(
                'Invalid payment number: %d. Must be between %d and %d payments.',
                $paymentNumber,
                $minPayments,
                $maxPayments
            )
        );
    }
} 