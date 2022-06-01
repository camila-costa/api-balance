<?php

use App\Controllers\AccountController;
use App\Controllers\EventController;
use App\Database\Database;
use App\Repositories\AccountRepository;
use App\Services\AccountService;
use App\Services\EventService;
use DI\Container;
use Psr\Container\ContainerInterface;

$repository = new AccountRepository($database);
$service = new AccountService($repository);

$container = new Container();

$container->set(AccountController::class, function (ContainerInterface $container) use ($service) {
    return new AccountController($service);
});

$container->set(EventController::class, function (ContainerInterface $container) use ($service) {
    $eventService = new EventService($service);
    return new EventController($eventService);
});

return $container;