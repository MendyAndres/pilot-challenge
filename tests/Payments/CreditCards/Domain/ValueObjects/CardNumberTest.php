<?php

namespace Tests\Unit\Payments\CreditCards\Domain\ValueObjects;

use App\Payments\CreditCards\Domain\Exceptions\InvalidCardNumberException;
use App\Payments\CreditCards\Domain\ValueObjects\CardNumber;
use PHPUnit\Framework\TestCase;

class CardNumberTest extends TestCase
{
    public function test_it_should_create_valid_card_number(): void
    {
        $cardNumber = new CardNumber('12345678');
        
        $this->assertEquals('12345678', $cardNumber->value());
    }

    public function test_it_should_throw_exception_for_invalid_length(): void
    {
        $this->expectException(InvalidCardNumberException::class);
        
        new CardNumber('123456');
    }

    public function test_it_should_compare_card_numbers_correctly(): void
    {
        $card1 = new CardNumber('12345678');
        $card2 = new CardNumber('12345678');
        $card3 = new CardNumber('87654321');
        
        $this->assertTrue($card1->equals($card2));
        $this->assertFalse($card1->equals($card3));
    }
} 