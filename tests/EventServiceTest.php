<?php

namespace Tests;

use App\Services\AccountService;
use App\Services\EventService;
use App\Services\EventValidationService;
use PHPUnit\Framework\TestCase;

class EventServiceTest extends TestCase
{
    private EventService $service;

    protected function setUp(): void
    {
        $accountStub = $this->createMock(AccountService::class);

        $validationStub = $this->createMock(EventValidationService::class);

        $validationStub->method('validateDeposit')
            ->willReturn(true);

        $validationStub->method('validateWithdraw')
            ->willReturn(true);

        $validationStub->method('validateTransfer')
            ->willReturn(true);

        $this->service = new EventService($accountStub, $validationStub);
    }

    public function testDeposit()
    {
        $data = [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10
        ];

        $response = $this->service->deposit($data);
        $expected = '{"destination":{"id":"100","balance":10}}';

        $this->assertEquals($response, $expected);
    }

    public function testWithdraw()
    {
        $data = [
            'type' => 'withdraw',
            'origin' => '100',
            'amount' => 10
        ];

        $response = $this->service->withdraw($data);
        $expected = '{"origin":{"id":"100","balance":-10}}';

        $this->assertEquals($response, $expected);
    }

    public function testTransfer()
    {
        $data = [
            'type' => 'transfer',
            'origin' => '100',
            'destination' => '200',
            'amount' => 10
        ];

        $response = $this->service->transfer($data);
        $expected = '{"origin":{"id":"100","balance":-10},"destination":{"id":"200","balance":10}}';

        $this->assertEquals($response, $expected);
    }
}
