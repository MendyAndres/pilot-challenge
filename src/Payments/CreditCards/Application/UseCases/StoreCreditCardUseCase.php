<?php

namespace App\Payments\CreditCards\Application\UseCases;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Repositories\CreditCardRepositoryInterface;

class StoreCreditCardUseCase
{

    public function __construct(private readonly CreditCardRepositoryInterface $repository){}

    public function execute(array $data): void
    {
        $creditCard = new CreditCardsEntity(
            (string)$data['number'],
            (string)$data['bank'],
            (string)$data['$holder'],
            (string)$data['$expirationDate'],
            (int)$data['cvv'],
            (float)$data['$amountLimit'],
            (string)$data['type'],
        );

        $this->repository->save($creditCard);
    }
}