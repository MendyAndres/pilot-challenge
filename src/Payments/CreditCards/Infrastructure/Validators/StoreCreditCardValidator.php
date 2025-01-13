<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Infrastructure\Validators;

use App\Payments\CreditCards\Infrastructure\Exceptions\ValidationException;

final class StoreCreditCardValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        if (!isset($data['number'])) {
            $errors['number'] = 'Card number is required';
        } elseif (!is_string($data['number']) || strlen($data['number']) !== 8) {
            $errors['number'] = 'Card number must be 8 digits';
        }

        if (!isset($data['bank'])) {
            $errors['bank'] = 'Bank is required';
        } elseif (!is_string($data['bank']) || empty(trim($data['bank']))) {
            $errors['bank'] = 'Bank must be a non-empty string';
        }

        if (!isset($data['holder'])) {
            $errors['holder'] = 'Holder name is required';
        } elseif (!is_string($data['holder']) || empty(trim($data['holder']))) {
            $errors['holder'] = 'Holder name must be a non-empty string';
        }

        if (!isset($data['holderDocument'])) {
            $errors['holderDocument'] = 'Holder document is required';
        } elseif (!is_string($data['holderDocument']) || empty(trim($data['holderDocument']))) {
            $errors['holderDocument'] = 'Holder document must be a non-empty string';
        }

        if (!isset($data['amountLimit'])) {
            $errors['amountLimit'] = 'Amount limit is required';
        } elseif (!is_numeric($data['amountLimit']) || $data['amountLimit'] <= 0) {
            $errors['amountLimit'] = 'Amount limit must be a positive number';
        }

        if (!isset($data['type'])) {
            $errors['type'] = 'Card type is required';
        } elseif (!in_array($data['type'], ['visa', 'amex'])) {
            $errors['type'] = 'Card type must be either visa or amex';
        }

        if (!empty($errors)) {
            throw ValidationException::fromArray($errors);
        }
    }
} 