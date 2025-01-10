<?php

use App\Payments\CreditCards\Infrastructure\Http\Actions\DoPaymentAction;
use App\Payments\CreditCards\Infrastructure\Http\Actions\StoreCreditCardAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\App;

return function (App $app) {

    $app->post('/credit-cards', [StoreCreditCardAction::class] );
    $app->post('/charges', [DoPaymentAction::class]);

    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });
};