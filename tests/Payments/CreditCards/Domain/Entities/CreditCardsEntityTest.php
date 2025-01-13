<?php

namespace Tests\Payments\CreditCards\Domain\Entities;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\ValueObjects\CardNumber;
use App\Payments\CreditCards\Domain\ValueObjects\CardType;
use App\Payments\CreditCards\Domain\Exceptions\InvalidCardTypeException;
use App\Payments\CreditCards\Domain\Exceptions\InvalidCardNumberException;
use PHPUnit\Framework\TestCase;

class CreditCardsEntityTest extends TestCase
{
    public function testCreateValidCreditCard(): void
    {
        $creditCard = new CreditCardsEntity(
            new CardNumber('12345678'),
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            CardType::fromString('visa')
        );

        $this->assertEquals('12345678', $creditCard->getNumber());
        $this->assertEquals('Galicia', $creditCard->getBank());
        $this->assertEquals('John Doe', $creditCard->getHolder());
        $this->assertEquals(1000.00, $creditCard->getAmountLimit());
        $this->assertEquals('visa', $creditCard->getType());
    }

    public function testInvalidCardTypeThrowsException(): void
    {
        $this->expectException(InvalidCardTypeException::class);
        $this->expectExceptionMessage('Invalid card type: mastercard. Must be Visa or AMEX');

        new CreditCardsEntity(
            new CardNumber('12345678'),
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            CardType::fromString('mastercard')
        );
    }

    public function testInvalidCardNumberThrowsException(): void
    {
        $this->expectException(InvalidCardNumberException::class);
        $this->expectExceptionMessage('Invalid card number: 123456. Must be 8 digits long.');

        new CreditCardsEntity(
            new CardNumber('123456'),  // Invalid: less than 8 digits
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            CardType::fromString('visa')
        );
    }

    public function testDecreaseLimit(): void
    {
        $creditCard = new CreditCardsEntity(
            new CardNumber('12345678'),
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            CardType::fromString('visa')
        );

        $creditCard->decreaseLimit(300.00);
        $this->assertEquals(700.00, $creditCard->getAmountLimit());
    }
} 