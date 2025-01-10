<?php

namespace Test\Payments\CreditCards\Domain\Service;

use App\Payments\CreditCards\Domain\Entities\CreditCardsEntity;
use App\Payments\CreditCards\Domain\Services\PostnetService;
use PHPUnit\Framework\TestCase;

class PostnetServiceTest extends TestCase
{
    private PostnetService $postnetService;
    protected function setUp(): void {
        parent::setUp();
        $this->postnetService = new PostnetService();
    }

    public function testFeeCalculationWithoutFee(): void
    {
        $creditCard = new CreditCardsEntity(
            '12345678',
            'Galicia +',
            'Andres Mendez',
            '116666666',
            1000.00,
            'Visa'
        );

        $result = $this->postnetService->doPayment($creditCard, 500.00, 1);

        $this->assertEquals(500.00, $result['totalAmount']);
        $this->assertEquals(500.00, $result['paymentsAmount']);
    }
}