<?php

namespace App\Responses;

class DepositEventResponse
{
    private string $id;
    private float $balance;

    public function __construct(string $id, float $balance)
    {
        $this->id = $id;
        $this->balance = $balance;
    }

    public function response(): string
    {
        $response = [
            'destination' => [
                'id' => $this->id,
                'balance' => $this->balance
            ]
        ];

        return json_encode($response);
    }
}