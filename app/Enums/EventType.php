<?php

namespace App\Enums;

class EventType
{
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
    const TRANSFER = 'transfer';

    public static function getAllTypes()
    {
        return [
            EventType::DEPOSIT,
            EventType::WITHDRAW,
            EventType::TRANSFER
        ];
    }
}