<?php

namespace App\Payments\CreditCards\Infrastructure\Repositories;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use PDO;

final class PdoCreditCardRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(CreditCardsEntity $creditCard): void
    {
        $statement = $this->pdo->prepare('INSERT INTO products (number, bank, holder, holderDocument, amountLimit, type) VALUES (:number, :bank, :holder, :holderDocument, :amountLimit, :type)');

        $statement->bindValue(':number', $creditCard->getNumber());
        $statement->bindValue(':bank',   $creditCard->getBank());
        $statement->bindValue(':holder', $creditCard->getHolder());
        $statement->bindValue(':holderDocument', $creditCard->getHolderDocument());
        $statement->bindValue(':amountLimit', $creditCard->getAmountLimit());
        $statement->bindValue(':type',       $creditCard->getType());

        $statement->execute();
    }

    public function find(string $creditCardNumber): ?CreditCardsEntity
    {
        $statement = $this->pdo->prepare('SELECT * FROM products WHERE number = :number');
        $statement->bindValue(':number', $creditCardNumber);
        $statement->execute();

        $creditCard = $statement->fetch(PDO::FETCH_ASSOC);
        if(!$creditCard){
            return null;
        }

        return $this->mapRowToEntity($creditCard);
    }



    public function mapRowToEntity(array $row): CreditCardsEntity
    {
        return new CreditCardsEntity(
            $row['number'],
            $row['bank'],
            $row['holder'],
            $row['holderDocument'],
            $row['amountLimit'],
            $row['type']
        );
    }
}