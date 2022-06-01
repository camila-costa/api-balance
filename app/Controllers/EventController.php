<?php

namespace App\Controllers;

use App\Exceptions\InvalidRequestException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Services\EventService;

class EventController
{
    private EventService $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function newEvent(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        try {
            $result = $this->service->processEvent($body);

            $response->getBody()->write($result);
            return $response->withStatus(200);
        } catch(InvalidRequestException $error) {
            $response->getBody()->write($error->getMessage());
            return $response->withStatus($error->getCode());
        }
    }
}