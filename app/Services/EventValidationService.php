<?php

namespace App\Services;

class EventValidationService
{
    public function validateType(array $data): bool
    {
        if (isset($data) && isset($data['type']))
            return true;

        return false;
    }

    public function validateDeposit(array $data): bool
    {
        if(isset($data) && isset($data['amount']) && isset($data['destination']))
            return true;
        
        return false;
    }

    public function validateWithdraw(array $data): bool
    {
        if (isset($data) && isset($data['amount']) && isset($data['origin']))
            return true;

        return false;
    }

    public function validateTransfer(array $data): bool
    {
        if($this->validateDeposit($data) && $this->validateWithdraw($data))
            return true;

        return false;
    }
}