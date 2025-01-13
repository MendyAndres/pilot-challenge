<?php

namespace App\Payments\CreditCards\Domain\Services;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Exceptions\InvalidPaymentNumberException;
use App\Payments\CreditCards\Domain\Exceptions\CreditCardNotFoundException;
use App\Payments\CreditCards\Domain\Exceptions\InsufficientLimitException;

class PostnetService
{
    public const int MAX_PAYMENT_NUMBER = 6;
    public const int MIN_PAYMENT_NUMBER = 1;
    private const float MONTHLY_INTEREST_RATE = 0.03;
    
    public function doPayment(?CreditCardsEntity $creditCardsEntity, float $amount, int $paymentNumber): array
    {
        $this->validCreditCard($creditCardsEntity);
        $this->validPaymentNumber($paymentNumber);

        $fee = $this->feeCalculation($amount, $paymentNumber);
        $totalAmount = $amount + $fee;

        if (!$creditCardsEntity->itHasEnoughLimit($totalAmount)) {
            throw new InsufficientLimitException($totalAmount, $creditCardsEntity->getAmountLimit());
        }

        $creditCardsEntity->decreaseLimit($totalAmount);

        return [
            'holderName' => $creditCardsEntity->getHolder(),
            'totalAmount' => $totalAmount,
            'paymentsAmount' => round($totalAmount / $paymentNumber, 2),
        ];
    }

    private function validPaymentNumber(int $paymentNumber): void
    {
        if ($paymentNumber < self::MIN_PAYMENT_NUMBER || $paymentNumber > self::MAX_PAYMENT_NUMBER) {
            throw new InvalidPaymentNumberException($paymentNumber, self::MIN_PAYMENT_NUMBER, self::MAX_PAYMENT_NUMBER);
        }
    }

    private function feeCalculation(float $amount, int $paymentNumber): float
    {
        return ($paymentNumber > 1) ? ($amount * (self::MONTHLY_INTEREST_RATE * ($paymentNumber - 1))) : 0;
    }

    private function validCreditCard(?CreditCardsEntity $creditCardsEntity): void
    {
        if (is_null($creditCardsEntity)) {
            throw new CreditCardNotFoundException('Card not provided');
        }
    }
}
