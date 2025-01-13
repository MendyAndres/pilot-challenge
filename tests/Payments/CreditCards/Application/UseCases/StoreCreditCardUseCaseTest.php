<?php

namespace Tests\Payments\CreditCards\Application\UseCases;

use App\Payments\CreditCards\Application\DTOs\StoreCreditCardDTO;
use App\Payments\CreditCards\Application\UseCases\StoreCreditCardUseCase;
use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Repositories\CreditCardRepositoryInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class StoreCreditCardUseCaseTest extends TestCase
{
    private StoreCreditCardUseCase $useCase;
    private MockObject|CreditCardRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getMockBuilder(CreditCardRepositoryInterface::class)
            ->getMock();
        $this->useCase = new StoreCreditCardUseCase($this->repository);
    }

    public function testSuccessfulStorage(): void
    {
        $dto = new StoreCreditCardDTO(
            '12345678',
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            'visa'
        );

        $this->repository
            ->method('save')
            ->with($this->isInstanceOf(CreditCardsEntity::class));

        $this->useCase->execute($dto);
        $this->assertTrue(true);
    }

    public function testInvalidDataThrowsException(): void
    {
        $dto = new StoreCreditCardDTO(
            '123', // Invalid number
            'Galicia',
            'John Doe',
            '116666666',
            1000.00,
            'visa'
        );

        $this->expectException(\DomainException::class);
        $this->useCase->execute($dto);
    }
} 