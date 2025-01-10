<?php

declare(strict_types=1);

use App\Payments\CreditCards\Domain\Repositories\CreditCardRepositoryInterface;
use App\Payments\CreditCards\Infrastructure\Repositories\PdoCreditCardRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
       CreditCardRepositoryInterface::class => function () {
           $dns = sprintf(
               'mysql:host=%s;port=%s;dbname=%s',
               $_ENV['DB_HOST'],
               $_ENV['DB_PORT'],
               $_ENV['DB_NAME']
           );

           $pdo = new PDO($dns, 'user', 'pass');
           $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

           return new PdoCreditCardRepository($pdo);
       }
    ]);
};