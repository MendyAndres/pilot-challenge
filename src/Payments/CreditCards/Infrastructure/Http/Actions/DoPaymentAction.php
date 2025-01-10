<?php

namespace App\Payments\CreditCards\Infrastructure\Http\Actions;

use App\Payments\CreditCards\Application\UseCases\DoPaymentUseCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DoPaymentAction
{
    public function __construct(private readonly DoPaymentUseCase $doPaymentUseCase){}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {

        try {
            $data = $request->getParsedBody();
            $ticketInformation = $this->doPaymentUseCase->execute($data['creditCardNumber'], $data['amount'], $data['paymentQty']);
            $response->getBody()->write(json_encode($ticketInformation));
            return $response->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            return $response->withStatus(400);
        }

    }
}