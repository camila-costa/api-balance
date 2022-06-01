<?php

namespace App\Services;

use App\Enums\EventType;
use App\Exceptions\InvalidRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Account;
use App\Responses\DepositEventResponse;
use App\Responses\WithdrawEventResponse;

class EventService
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function processEvent(array $data): string
    {
        switch ($data['type']) {
            case EventType::DEPOSIT:
                return $this->deposit($data);
            case EventType::WITHDRAW:
                return $this->withdraw($data);
            case EventType::TRANSFER:
                return $this->transfer($data);
            default:
                throw new InvalidRequestException('Invalid event type');
        }
    }

    private function deposit(array $data): string
    {
        $id = $data['destination'];
        $value = $data['amount'];

        try {
            $balance = $this->accountService->getBalanceById($id);
            $value += $balance;
            $account = new Account($id, $value);
            $this->accountService->update($account);
        } catch(NotFoundException $error) {
            $account = new Account($id, $value);
            $this->accountService->save($account);
        }

        $response = new DepositEventResponse($id, $value);
        return $response->response();
    }

    private function withdraw(array $data): string
    {
        $id = $data['origin'];
        $value = $data['amount'];

        $balance = $this->accountService->getBalanceById($id);
        $value = $balance - $value;
        $account = new Account($id, $value);
        $this->accountService->update($account);

        $response = new WithdrawEventResponse($id, $value);
        return $response->response();
    }

    private function transfer(array $data): string
    {
        $withdrawResponse = $this->withdraw(['origin' => $data['origin'], 'amount' => $data['amount']]);
        $depositResponse = $this->deposit(['destination' => $data['destination'], 'amount' => $data['amount']]);

        $response = array_merge(json_decode($withdrawResponse, true), json_decode($depositResponse, true));

        return json_encode($response);
    }
}