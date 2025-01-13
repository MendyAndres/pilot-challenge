<?php

declare(strict_types=1);
namespace App\Payments\CreditCards\Domain\Repositories;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;

interface CreditCardRepositoryInterface
{
    public function save(CreditCardsEntity $creditCard): void;
    public function find(string $creditCardNumber): ?CreditCardsEntity;


}