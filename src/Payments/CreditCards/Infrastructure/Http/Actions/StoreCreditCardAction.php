<?php

namespace App\Payments\CreditCards\Infrastructure\Http\Actions;

use App\Payments\CreditCards\Application\UseCases\StoreCreditCardUseCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class StoreCreditCardAction
{
    public function __construct(private readonly StoreCreditCardUseCase $storeCreditCardUseCase){}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = (array) $request->getParsedBody();
            $this->storeCreditCardUseCase->execute($data);
            $response->getBody()->write(json_encode(['message' => "Credit Card Saved"]));

            return $response->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            return $response->withStatus(400);
        }
    }
}