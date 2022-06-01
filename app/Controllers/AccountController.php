<?php

namespace App\Controllers;

use App\Models\Account;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Services\AccountService;

class AccountController
{
    private AccountService $service;

    public function __construct(AccountService $service)
    {
        $this->service = $service;
    }

    public function getBalance(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        if(!isset($params['account_id'])) {
            return $response
                ->withStatus(404);
        }

        $id = $params['account_id'];

        /* $account = new Account("1", 10);
        $this->service->save($account); */

        $balance = $this->service->getBalanceById($id);

        if(is_null($balance))
            return $response->withStatus(404);

        $response->getBody()->write(
            (string) $balance
        );

        return $response->withStatus(200);
    }
}
