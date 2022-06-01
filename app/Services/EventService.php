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

    private function deposit(array $data, $createAccount = true): string
    {
        $id = $data['destination'];
        $value = $data['value'];

        try {
            $balance = $this->accountService->getBalanceById($id);
            $value += $balance;
            $account = new Account($id, $value);
            $this->accountService->update($account);
        } catch(NotFoundException $error) {
            if($createAccount) {
                $account = new Account($id, $value);
                $this->accountService->save($account);
            } else {
                throw $error;
            }
        }

        $response = new DepositEventResponse($id, $value);
        return $response->response();
    }

    private function withdraw(array $data): string
    {
        $id = $data['origin'];
        $value = $data['value'];

        $balance = $this->accountService->getBalanceById($id);
        $value = $balance - $value;
        $account = new Account($id, $value);
        $this->accountService->update($account);

        $response = new WithdrawEventResponse($id, $value);
        return $response->response();
    }

    private function transfer(array $data): string
    {
        $withdrawResponse = $this->withdraw(['origin' => $data['origin'], 'value' => $data['value']]);
        $depositResponse = $this->deposit(['destination' => $data['destination'], 'value' => $data['value']], false);

        $response = array_merge(json_decode($withdrawResponse, true), json_decode($depositResponse, true));

        return json_encode($response);
    }
}