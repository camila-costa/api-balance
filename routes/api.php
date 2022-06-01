<?php

use App\Controllers\AccountController;
use App\Database\InMemory;
use App\Repositories\AccountRepository;
use App\Services\AccountService;
use DI\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$database = InMemory::getInstance();
$repository = new AccountRepository($database);
$service = new AccountService($repository);

$container->set(AccountController::class, function (ContainerInterface $container) use ($service) {
    return new AccountController($service);
});

$app = AppFactory::createFromContainer($container);

$app->get('/balance', AccountController::class . ':getBalance');

return $app;