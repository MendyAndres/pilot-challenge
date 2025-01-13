<?php

namespace App\Payments\CreditCards\Infrastructure\Http\Actions;

use App\Payments\CreditCards\Application\DTOs\StoreCreditCardDTO;
use App\Payments\CreditCards\Application\UseCases\StoreCreditCardUseCase;
use App\Payments\CreditCards\Infrastructure\Exceptions\ValidationException;
use App\Payments\CreditCards\Infrastructure\Validators\StoreCreditCardValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use DomainException;

class StoreCreditCardAction
{
    public function __construct(private readonly StoreCreditCardUseCase $storeCreditCardUseCase){}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = (array) $request->getParsedBody();
            StoreCreditCardValidator::validate($data);
            
            $this->storeCreditCardUseCase->execute(StoreCreditCardDTO::fromArray($data));
            
            return $this->jsonResponse($response, ['message' => 'Credit Card Saved'], 201);
        } catch (ValidationException $e) {
            return $this->jsonResponse($response, ['errors' => $e->getErrors()], 422);
        } catch (DomainException $e) {
            return $this->jsonResponse($response, ['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            // Log the error
            return $this->jsonResponse($response, ['error' => 'Internal Server Error'], 500);
        }
    }

    private function jsonResponse(ResponseInterface $response, array $data, int $statusCode): ResponseInterface
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($statusCode)
            ->withHeader('Content-Type', 'application/json');
    }
}