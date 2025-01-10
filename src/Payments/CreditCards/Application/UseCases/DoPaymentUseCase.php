<?php

namespace App\Payments\CreditCards\Application\UseCases;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Repositories\CreditCardRepositoryInterface;
use App\Payments\CreditCards\Domain\Services\PostnetService;

final readonly class DoPaymentUseCase
{
    public function __construct(private CreditCardRepositoryInterface $repository){}

    public function execute(string $creditCardNumber, float $amount, int $paymentNumber): array
    {
        $creditCard = $this->repository->find($creditCardNumber);
        $postNet = new PostnetService();
        return $postNet->doPayment($creditCard, $amount, $paymentNumber);
    }
}