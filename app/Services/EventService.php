<?php

namespace App\Services;

use App\Enums\EventType;
use App\Exceptions\InvalidRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Account;

class EventService
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function processEvent(array $data)
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

    private function deposit(array $data)
    {
        $id = $data['destination'];
        $value = $data['value'];

        try {
            $balance = $this->accountService->getBalanceById($id);
            $value += $balance;
            $account = new Account($id, $value);
            $this->accountService->update($account);
        } catch(NotFoundException $error) {
            $account = new Account($id, $data['value']);
            $this->accountService->save($account);
        }

        return "deposit ";
    }

    private function withdraw(array $data)
    {
       return "withdraw";
    }

    private function transfer(array $data)
    {
        return "transfer";
    }
}