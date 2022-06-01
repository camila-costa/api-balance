<?php

namespace App\Models;

class Account
{
    private string $id;
    private float $balance;

    public function __construct(string $id, float $balance)
    {
        $this->id = $id;
        $this->balance = $balance;
    }

    public function getId() :string
    {
        return $this->id;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'balance' => $this->balance,
        ];
    }
}