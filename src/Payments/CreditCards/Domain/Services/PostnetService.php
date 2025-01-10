<?php

namespace App\Payments\CreditCards\Domain\Services;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;

class PostnetService
{
    public function doPayment(CreditCardsEntity $creditCardsEntity, float $amount, int $paymentQty): array
    {
        $this->validPaymentQty($paymentQty);
        $fee =  $this->feeCalculation($amount, $paymentQty);
        $totalAmount = $amount + $fee;

        if (!$creditCardsEntity->itHasEnoughLimit($totalAmount)) {
            throw new \Exception('Insufficient credit limit');
        }

        $creditCardsEntity->decreaseLimit($totalAmount);

        return [
            'holderName' => $creditCardsEntity->getHolder(),
            'totalAmount' => $totalAmount,
            'paymentsAmount' => round($totalAmount / $paymentQty, 2),
        ];
    }

    private function validPaymentQty(int $paymentQty): void
    {
        if ($paymentQty < 1 || $paymentQty > 6) {
            throw new \Exception('Invalid payment quantity');
        }
    }

    private function feeCalculation(float $amount, int $paymentQty): float
    {
        return ($paymentQty > 1) ? ($amount * (0.03 * ($paymentQty - 1))) : 0;
    }
}