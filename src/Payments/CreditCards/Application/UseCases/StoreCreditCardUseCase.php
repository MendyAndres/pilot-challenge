<?php

namespace App\Payments\CreditCards\Application\UseCases;

use App\Payments\CreditCards\Application\DTOs\StoreCreditCardDTO;
use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Repositories\CreditCardRepositoryInterface;
use App\Payments\CreditCards\Domain\ValueObjects\CardNumber;
use App\Payments\CreditCards\Domain\ValueObjects\CardType;

class StoreCreditCardUseCase
{

    public function __construct(private readonly CreditCardRepositoryInterface $repository){}

    public function execute(StoreCreditCardDTO $dto): void
    {
        $creditCard = new CreditCardsEntity(
            new CardNumber($dto->number),
            $dto->bank,
            $dto->holder,
            $dto->holderDocument,
            $dto->amountLimit,
            CardType::fromString($dto->type),
        );

        $this->repository->save($creditCard);
    }
}