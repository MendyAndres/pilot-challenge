<?php

namespace Tests\Payments\CreditCards\Domain\Services;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Services\PostnetService;
use App\Payments\CreditCards\Domain\ValueObjects\CardNumber;
use App\Payments\CreditCards\Domain\ValueObjects\CardType;
use App\Payments\CreditCards\Domain\Exceptions\InvalidPaymentNumberException;
use App\Payments\CreditCards\Domain\Exceptions\InsufficientLimitException;
use PHPUnit\Framework\TestCase;

class PostnetServiceTest extends TestCase
{
    private PostnetService $postnetService;
    private CreditCardsEntity $creditCard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postnetService = new PostnetService();
        $this->creditCard = new CreditCardsEntity(
            new CardNumber('12345678'),
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            CardType::fromString('visa')
        );
    }

    public function testFeeCalculationWithoutFee(): void
    {
        $result = $this->postnetService->doPayment($this->creditCard, 500.00, 1);

        $this->assertEquals(500.00, $result['totalAmount']);
        $this->assertEquals(500.00, $result['paymentsAmount']);
        $this->assertEquals('John Doe', $result['holderName']);
    }

    public function testMultiplePaymentsWithFee(): void
    {
        $result = $this->postnetService->doPayment($this->creditCard, 500.00, 3);
        
        // Fee calculation: 500 * (0.03 * 2) = 30
        // Total amount: 500 + 30 = 530
        $this->assertEquals(530.00, $result['totalAmount']);
        $this->assertEquals(176.67, $result['paymentsAmount']);
    }

    public function testPaymentExceedingLimitThrowsException(): void
    {
        $creditCard = new CreditCardsEntity(
            new CardNumber('12345678'),
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            CardType::fromString('visa')
        );

        $this->expectException(InsufficientLimitException::class);
        $this->expectExceptionMessage('Insufficient credit limit. Requested amount: 2000.00, Available limit: 1000.00');

        $this->postnetService->doPayment($creditCard, 2000.00, 1);
    }

    public function testInvalidPaymentNumberThrowsException(): void
    {
        $creditCard = new CreditCardsEntity(
            new CardNumber('12345678'),
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            CardType::fromString('visa')
        );

        $this->expectException(InvalidPaymentNumberException::class);
        $this->expectExceptionMessage('Invalid payment number: 7. Must be between 1 and 6 payments.');

        $this->postnetService->doPayment($creditCard, 500.00, 7);
    }
} 