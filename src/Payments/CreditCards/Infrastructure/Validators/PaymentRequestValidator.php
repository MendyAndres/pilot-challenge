<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Infrastructure\Validators;

use App\Payments\CreditCards\Infrastructure\Exceptions\ValidationException;
use App\Payments\CreditCards\Domain\Services\PostnetService;

final class PaymentRequestValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        if (!isset($data['creditCardNumber'])) {
            $errors['creditCardNumber'] = 'Credit card number is required';
        } elseif (!is_string($data['creditCardNumber']) || strlen($data['creditCardNumber']) !== 8) {
            $errors['creditCardNumber'] = 'Credit card number must be 8 digits';
        }

        if (!isset($data['amount'])) {
            $errors['amount'] = 'Amount is required';
        } elseif (!is_numeric($data['amount']) || $data['amount'] <= 0) {
            $errors['amount'] = 'Amount must be a positive number';
        }

        if (!isset($data['paymentNumber'])) {
            $errors['paymentNumber'] = 'Payment number is required';
        } elseif (!is_int($data['paymentNumber']) || 
            $data['paymentNumber'] < PostnetService::MIN_PAYMENT_NUMBER || 
            $data['paymentNumber'] > PostnetService::MAX_PAYMENT_NUMBER
        ) {
            $errors['paymentNumber'] = sprintf(
                'Payment number must be between %d and %d',
                PostnetService::MIN_PAYMENT_NUMBER,
                PostnetService::MAX_PAYMENT_NUMBER
            );
        }

        if (!empty($errors)) {
            throw ValidationException::fromArray($errors);
        }
    }
} 