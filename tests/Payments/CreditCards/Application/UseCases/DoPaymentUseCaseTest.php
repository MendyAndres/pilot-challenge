<?php

namespace Tests\Payments\CreditCards\Application\UseCases;

use App\Payments\CreditCards\Application\DTOs\PaymentRequestDTO;
use App\Payments\CreditCards\Application\UseCases\DoPaymentUseCase;
use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Repositories\CreditCardRepositoryInterface;
use App\Payments\CreditCards\Domain\Services\PostnetService;
use App\Payments\CreditCards\Domain\ValueObjects\CardNumber;
use App\Payments\CreditCards\Domain\ValueObjects\CardType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Payments\CreditCards\Domain\Exceptions\CreditCardNotFoundException;
use App\Payments\CreditCards\Domain\Exceptions\InsufficientLimitException;

class DoPaymentUseCaseTest extends TestCase
{
    private DoPaymentUseCase $useCase;
    private MockObject|CreditCardRepositoryInterface $repository;
    private MockObject|PostnetService $postnetService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mocks with more specific configuration
        $this->repository = $this->createMock(CreditCardRepositoryInterface::class);
        $this->postnetService = $this->createMock(PostnetService::class);
        $this->useCase = new DoPaymentUseCase($this->repository, $this->postnetService);
    }

    public function testSuccessfulPayment(): void
    {
        $creditCard = new CreditCardsEntity(
            new CardNumber('12345678'),
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            CardType::fromString('visa'),
        );

        // Configure mock using MockObject methods
        $this->repository
            ->method('find')
            ->with('12345678')
            ->willReturn($creditCard);

        $this->postnetService
            ->method('doPayment')
            ->with($creditCard, 500.00, 1)
            ->willReturn([
                'holderName' => 'John Doe',
                'totalAmount' => 500.00,
                'paymentsAmount' => 500.00,
            ]);

        $doPaymentUseCase = new DoPaymentUseCase($this->repository, $this->postnetService);   
        $result = $doPaymentUseCase->execute(PaymentRequestDTO::fromArray(['creditCardNumber' => '12345678', 'amount' => 500.00, 'paymentNumber' => 1]));

        $this->assertEquals(500.00, $result->totalAmount);
        $this->assertEquals(500.00, $result->paymentsAmount);
        $this->assertEquals('John Doe', $result->holderName);
    }

    public function testCardNotFound(): void
    {
        // Configure repository mock
        $this->repository
            ->method('find')
            ->with('99999999')
            ->willReturn(null);

        $this->expectException(CreditCardNotFoundException::class);
        $this->expectExceptionMessage('Credit card with number 99999999 was not found.');
        
        $this->useCase->execute(PaymentRequestDTO::fromArray([
            'creditCardNumber' => '99999999', 
            'amount' => 500.00, 
            'paymentNumber' => 1
        ]));
    }

    public function testInsufficientLimitThrowsException(): void
    {
        $this->expectException(InsufficientLimitException::class);
        
        $creditCard = new CreditCardsEntity(
            new CardNumber('12345678'),
            'Galicia',
            'John Doe',
            '116666666',
            100.00, // Low limit
            CardType::fromString('visa'),
        );

        $this->repository->method('find')->willReturn($creditCard);
        
        $this->postnetService
            ->method('doPayment')
            ->willThrowException(new InsufficientLimitException(500.00, 100.00));
        
        $this->useCase->execute(PaymentRequestDTO::fromArray([
            'creditCardNumber' => '12345678',
            'amount' => 500.00,
            'paymentNumber' => 1
        ]));
    }
} 