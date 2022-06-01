<?php

use App\Controllers\AccountController;
use App\Controllers\EventController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

$app = AppFactory::createFromContainer($container);
$app->addBodyParsingMiddleware();

$app->post('/reset', function (Request $request, Response $response, array $args) use ($database) {
    $database->clearAll('accounts');
    $response->getBody()->write('OK');
    return $response->withStatus(200);
});

$app->get('/balance', AccountController::class . ':getBalance');

$app->post('/event', EventController::class . ':newEvent');

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$customErrorHandler = function () use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    return $response->withStatus(404);
};
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, $customErrorHandler);

return $app;