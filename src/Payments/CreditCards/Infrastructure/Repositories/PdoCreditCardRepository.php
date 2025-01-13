<?php

namespace App\Payments\CreditCards\Infrastructure\Repositories;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Repositories\CreditCardRepositoryInterface;
use App\Payments\CreditCards\Infrastructure\Exceptions\DatabaseException;
use PDO;

final class PdoCreditCardRepository implements CreditCardRepositoryInterface{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(CreditCardsEntity $creditCard): void
    {
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('INSERT INTO credit_cards (number, bank, holder, holderDocument, amountLimit, type) VALUES (:number, :bank, :holder, :holderDocument, :amountLimit, :type)');

            $statement->bindValue(':number', $creditCard->getNumber());
            $statement->bindValue(':bank',   $creditCard->getBank());
            $statement->bindValue(':holder', $creditCard->getHolder());
            $statement->bindValue(':holderDocument', $creditCard->getHolderDocument());
            $statement->bindValue(':amountLimit', $creditCard->getAmountLimit());
            $statement->bindValue(':type',       $creditCard->getType());

            $statement->execute();
            
            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw new DatabaseException('Failed to save credit card', 500, $e);
        }
        
    }

    public function find(string $creditCardNumber): ?CreditCardsEntity
    {
        try {
            $statement = $this->pdo->prepare('SELECT * FROM credit_cards WHERE number = :number');
            $statement->bindValue(':number', $creditCardNumber);
            $statement->execute();

            $creditCard = $statement->fetch(PDO::FETCH_ASSOC);
            return $creditCard ? $this->mapRowToEntity($creditCard) : null;
        } catch (\PDOException $e) {
            // Log the error
            throw new DatabaseException('Failed to fetch credit card', 500, $e);
        }
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