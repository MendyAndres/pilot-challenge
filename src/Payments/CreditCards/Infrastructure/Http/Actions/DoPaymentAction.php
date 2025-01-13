<?php

namespace App\Payments\CreditCards\Infrastructure\Http\Actions;

use App\Payments\CreditCards\Application\UseCases\DoPaymentUseCase;
use App\Payments\CreditCards\Application\DTOs\PaymentRequestDTO;
use App\Payments\CreditCards\Application\DTOs\PaymentInformationDTO;
use App\Payments\CreditCards\Infrastructure\Exceptions\ValidationException;
use App\Payments\CreditCards\Infrastructure\Validators\PaymentRequestValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use DomainException;

class DoPaymentAction
{
    public function __construct(private readonly DoPaymentUseCase $doPaymentUseCase){}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        try {
            $data = $request->getParsedBody();
            PaymentRequestValidator::validate($data);
            
            $dto = PaymentRequestDTO::fromArray($data);
            $result = $this->doPaymentUseCase->execute($dto);
            
            return $this->jsonResponse($response, $result, 200);
        } catch (ValidationException $e) {
            return $this->jsonResponse($response, ['errors' => $e->getErrors()], 422);
        } catch (DomainException $e) {
            return $this->jsonResponse($response, ['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            // Log the error
            return $this->jsonResponse($response, ['error' => 'Internal Server Error'], 500);
        }
    }

    private function jsonResponse(ResponseInterface $response, PaymentInformationDTO|array $data, int $statusCode): ResponseInterface
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($statusCode)
            ->withHeader('Content-Type', 'application/json');
    }
}