<?php

use Dotenv\Dotenv;

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../app/dependencies.php')($containerBuilder);
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

(require __DIR__ . '/../app/routes.php')($app);

$app->run();
