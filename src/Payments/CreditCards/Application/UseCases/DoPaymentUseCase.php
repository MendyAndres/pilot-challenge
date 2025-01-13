<?php

namespace App\Payments\CreditCards\Application\UseCases;

use App\Payments\CreditCards\Application\DTOs\PaymentInformationDTO;
use App\Payments\CreditCards\Application\DTOs\PaymentRequestDTO;
use App\Payments\CreditCards\Domain\Repositories\CreditCardRepositoryInterface;
use App\Payments\CreditCards\Domain\Services\PostnetService;
use App\Payments\CreditCards\Domain\Exceptions\CreditCardNotFoundException;
use App\Payments\CreditCards\Domain\Exceptions\InsufficientLimitException;

class DoPaymentUseCase
{
    public function __construct(
        private readonly CreditCardRepositoryInterface $repository, 
        private readonly PostnetService $postnetService){}

    public function execute(PaymentRequestDTO $dto): PaymentInformationDTO
    {
        $creditCard = $this->repository->find($dto->creditCardNumber);
        
        if (!$creditCard) {
            throw new CreditCardNotFoundException($dto->creditCardNumber);
        }
        
        try {
            $paymentInformation = $this->postnetService->doPayment($creditCard, $dto->amount, $dto->paymentNumber);
            return PaymentInformationDTO::fromArray($paymentInformation);
        } catch (InsufficientLimitException $e) {
            throw $e;
        }
    }
}