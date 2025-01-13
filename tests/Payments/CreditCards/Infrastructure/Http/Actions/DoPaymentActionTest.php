<?php

namespace Tests\Payments\CreditCards\Infrastructure\Http\Actions;

use App\Payments\CreditCards\Application\UseCases\DoPaymentUseCase;
use App\Payments\CreditCards\Infrastructure\Http\Actions\DoPaymentAction;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class DoPaymentActionTest extends TestCase
{
    private DoPaymentAction $action;
    private DoPaymentUseCase|MockObject $useCase;
    private ServerRequestInterface|MockObject $request;
    private ResponseInterface|MockObject $response;
    private StreamInterface|MockObject $stream;

    protected function setUp(): void
    {
        $this->useCase = $this->createMock(DoPaymentUseCase::class);
        $this->action = new DoPaymentAction($this->useCase);
        
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
        
        $this->response->method('getBody')->willReturn($this->stream);
        $this->response->method('withStatus')->willReturn($this->response);
        $this->response->method('withHeader')->willReturn($this->response);
    }

    public function testValidationFailsWhenCreditCardNumberIsMissing(): void
    {
        $this->request->method('getParsedBody')
            ->willReturn([
                'amount' => 500.00,
                'paymentNumber' => 1
            ]);

        $this->stream->expects($this->once())
            ->method('write')
            ->with($this->callback(function ($json) {
                $data = json_decode($json, true);
                return isset($data['errors']['creditCardNumber']) && $data['errors']['creditCardNumber'] === 'Credit card number is required';
            }));

        $this->response->expects($this->once())
            ->method('withStatus')
            ->with(422);

        ($this->action)($this->request, $this->response);
    }
} 