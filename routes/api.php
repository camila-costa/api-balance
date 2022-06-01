<?php

use App\Controllers\AccountController;
use App\Controllers\EventController;
use App\Database\Database;
use App\Database\InMemory;
use App\Repositories\AccountRepository;
use App\Services\AccountService;
use App\Services\EventService;
use DI\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$dns = 'mysql:host=localhost;dbname=apibalance';
$usuario = 'root';
$senha = '';

$pdo = new PDO($dns, $usuario, $senha);

$database = new Database($pdo);
$repository = new AccountRepository($database);
$service = new AccountService($repository);

$container->set(AccountController::class, function (ContainerInterface $container) use ($service) {
    return new AccountController($service);
});

$container->set(EventController::class, function (ContainerInterface $container) use ($service) {
    $eventService = new EventService($service);
    return new EventController($eventService);
});

$app = AppFactory::createFromContainer($container);
$app->addBodyParsingMiddleware();

$app->post('/reset', function (Request $request, Response $response, array $args) use ($database) {
    $database->clearAll('accounts');
    $response->getBody()->write('OK');
    return $response->withStatus(200);
});

$app->get('/balance', AccountController::class . ':getBalance');
$app->post('/event', EventController::class . ':newEvent');

return $app;