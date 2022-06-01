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
    private EventValidationService $eventValidationService;

    public function __construct(AccountService $accountService, EventValidationService $eventValidationService)
    {
        $this->accountService = $accountService;
        $this->eventValidationService = $eventValidationService;
    }

    public function processEvent(array $data): string
    {
        if (!$this->eventValidationService->validateType($data))
            throw new InvalidRequestException('Invalid fields');

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

    public function deposit(array $data): string
    {
        if (!$this->eventValidationService->validateDeposit($data))
            throw new InvalidRequestException('Invalid fields');
        
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

    public function withdraw(array $data): string
    {
        if (!$this->eventValidationService->validateWithdraw($data))
            throw new InvalidRequestException('Invalid fields');

        $id = $data['origin'];
        $value = $data['amount'];

        $balance = $this->accountService->getBalanceById($id);
        $value = $balance - $value;
        $account = new Account($id, $value);
        $this->accountService->update($account);

        $response = new WithdrawEventResponse($id, $value);
        return $response->response();
    }

    public function transfer(array $data): string
    {
        if (!$this->eventValidationService->validateTransfer($data))
            throw new InvalidRequestException('Invalid fields');

        $withdrawResponse = $this->withdraw($data);
        $depositResponse = $this->deposit($data);

        $response = array_merge(
            json_decode($withdrawResponse, true),
            json_decode($depositResponse, true)
        );

        return json_encode($response);
    }
}